<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\ReportAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Report::with(['category', 'user']);

        // Multi-tenant District Isolation untuk Petugas Terautentikasi
        if ($user->role !== 'vendor_admin' && !empty($user->district)) {
            $userDistrict = mb_strtolower(trim($user->district));
            $query->whereRaw('LOWER(district) = ?', [$userDistrict]);
        }

        // Dropdown District Filter
        if ($request->filled('district')) {
            $reqDistrict = mb_strtolower(trim($request->district));
            $query->whereRaw('LOWER(district) = ?', [$reqDistrict]);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('report_number', 'like', '%' . $request->search . '%')
                  ->orWhere('subdistrict', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        if ($user && $request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(12);
        $categories = Category::all();

        // Get distinct list of districts for dropdown
        $districtsQuery = Report::select('district')
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->pluck('district')
            ->toArray();

        $defaultDistricts = ['Kabupaten Bogor', 'Kabupaten Deli Serdang', 'Kota Bogor', 'Kota Bandung', 'Kabupaten Bekasi', 'Kabupaten Tangerang'];
        $districts = array_values(array_unique(array_merge($districtsQuery, $defaultDistricts)));
        sort($districts);

        return view('reports.index', compact('reports', 'categories', 'districts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('reports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'chronology' => 'required|string',
            'risk_level' => 'required|in:green,yellow,red',
            'incident_date' => 'required|date',
            'district' => 'required|string|max:100',
            'subdistrict' => 'required|string|max:100',
            'village' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'reporter_name' => Auth::check() ? 'nullable|string|max:100' : 'required|string|max:100',
            'reporter_phone' => 'nullable|string|max:20',
            'is_anonymous' => 'nullable|boolean',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $lastId = Report::max('id') ?? 0;
        $reportNumber = 'LAP-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('reports', 'public');
        }

        $isAnon = $request->boolean('is_anonymous');
        $reporterName = Auth::check()
            ? Auth::user()->name
            : ($validated['reporter_name'] ?? 'Pelapor Masyarakat');

        $reporterPhone = Auth::check()
            ? Auth::user()->phone
            : ($validated['reporter_phone'] ?? null);

        if ($isAnon) {
            $reporterName = 'Masyarakat Anonim';
            $reporterPhone = null;
        }

        $report = Report::create([
            'report_number' => $reportNumber,
            'user_id' => Auth::check() ? Auth::id() : null,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'chronology' => $validated['chronology'],
            'risk_level' => $validated['risk_level'],
            'status' => 'pending',
            'incident_date' => $validated['incident_date'],
            'district' => $validated['district'],
            'subdistrict' => $validated['subdistrict'],
            'village' => $validated['village'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'] ?? -6.2088,
            'longitude' => $validated['longitude'] ?? 106.8456,
            'attachment_path' => $attachmentPath,
            'reporter_name' => $reporterName,
            'reporter_phone' => $reporterPhone,
            'is_anonymous' => $isAnon,
        ]);

        if (Auth::check()) {
            ReportAction::create([
                'report_id' => $report->id,
                'user_id' => Auth::id(),
                'action_type' => 'creation',
                'note' => 'Laporan deteksi dini baru dibuat dengan Nomor: ' . $reportNumber,
            ]);
        }

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Laporan informasi deteksi dini berhasil dikirimkan dengan Nomor: ' . $reportNumber);
    }

    public function show($id)
    {
        $report = Report::with(['category', 'user', 'actions.user'])->findOrFail($id);

        $user = Auth::user();

        // 1. Citizen authorization check
        if ($user && $user->isCitizen() && $report->user_id !== $user->id) {
            return redirect()->route('reports.index')->with('error', 'Anda tidak memiliki otorisasi melihat laporan ini.');
        }

        // 2. District Isolation Check (Semua role kecuali Vendor Admin hanya bisa melihat laporan dari Kabupaten yang sama)
        if ($user && $user->role !== 'vendor_admin' && !empty($user->district) && !empty($report->district)) {
            if (mb_strtolower(trim($user->district)) !== mb_strtolower(trim($report->district))) {
                return redirect()->route('reports.index')->with('error', 'Anda tidak memiliki otorisasi melihat laporan dari Kabupaten/Kota lain.');
            }
        }

        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $report = Report::findOrFail($id);

        if (!$user->canManageReport($report)) {
            return back()->with('error', 'Otorisasi ditolak: Pembaruan status laporan ini berada di luar wewenang kecamatan terdaftar Anda.');
        }

        $request->validate([
            'status' => 'required|in:pending,verified,in_progress,resolved,rejected',
            'risk_level' => 'required|in:green,yellow,red',
            'note' => 'required|string',
        ]);

        $oldStatus = $report->status_label;
        $report->status = $request->status;
        $report->risk_level = $request->risk_level;
        $report->save();

        ReportAction::create([
            'report_id' => $report->id,
            'user_id' => $user->id,
            'action_type' => 'status_update',
            'note' => "Status diperbarui dari [{$oldStatus}] menjadi [{$report->status_label}]. Catatan Petugas: {$request->note}",
        ]);

        return back()->with('success', 'Status & tindak lanjut laporan berhasil diperbarui.');
    }

    public function print($id)
    {
        $report = Report::with(['category', 'user', 'actions.user'])->findOrFail($id);
        return view('reports.print', compact('report'));
    }
}
