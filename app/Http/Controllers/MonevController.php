<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Evaluation;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonevController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Filter Laporan Evaluasi Eksekutif
        $evalQuery = Evaluation::with('user');
        if ($user && $user->role !== 'vendor_admin' && !empty($user->district)) {
            $userDistrict = mb_strtolower(trim($user->district));
            $evalQuery->where(function ($q) use ($userDistrict) {
                $q->whereRaw('LOWER(target_region) LIKE ?', ['%' . $userDistrict . '%'])
                  ->orWhereHas('user', function ($uq) use ($userDistrict) {
                      $uq->whereRaw('LOWER(district) = ?', [$userDistrict]);
                  });
            });
        }
        $evaluations = $evalQuery->latest()->paginate(10);

        // 2. Base Query Laporan dengan Isolasi Wilayah Kabupaten
        $reportQuery = Report::query();
        if ($user && $user->role !== 'vendor_admin' && !empty($user->district)) {
            $userDistrict = mb_strtolower(trim($user->district));
            $reportQuery->whereRaw('LOWER(district) = ?', [$userDistrict]);
        }

        // Overall Monev Metrics
        $totalReports = (clone $reportQuery)->count();
        $resolvedReports = (clone $reportQuery)->where('status', 'resolved')->count();
        $inProgressReports = (clone $reportQuery)->where('status', 'in_progress')->count();
        
        $resolutionRate = $totalReports > 0 ? round(($resolvedReports / $totalReports) * 100, 1) : 0;

        // Calculate Indeks Kerawanan Wilayah (IKW) Score (0-100)
        $redCount = (clone $reportQuery)->where('risk_level', 'red')->count();
        $yellowCount = (clone $reportQuery)->where('risk_level', 'yellow')->count();
        $riskIndexScore = min(100, round(($redCount * 25) + ($yellowCount * 10) + 15, 1));

        // Subdistricts Compliance Leaderboard & Incident Distribution
        $subdistrictStats = (clone $reportQuery)->select('subdistrict', 
                DB::raw('count(*) as total_reports'),
                DB::raw("sum(case when risk_level = 'red' then 1 else 0 end) as red_reports"),
                DB::raw("sum(case when status = 'resolved' then 1 else 0 end) as resolved_reports")
            )
            ->groupBy('subdistrict')
            ->get();

        // Count categories scoped by user district
        $categories = Category::withCount(['reports' => function ($q) use ($user) {
            if ($user && $user->role !== 'vendor_admin' && !empty($user->district)) {
                $q->whereRaw('LOWER(district) = ?', [mb_strtolower(trim($user->district))]);
            }
        }])->get();

        return view('monev.index', compact(
            'evaluations',
            'totalReports',
            'resolvedReports',
            'inProgressReports',
            'resolutionRate',
            'riskIndexScore',
            'subdistrictStats',
            'categories'
        ));
    }

    public function createBuilder()
    {
        $user = Auth::user();
        $reportQuery = Report::query();
        if ($user && $user->role !== 'vendor_admin' && !empty($user->district)) {
            $userDistrict = mb_strtolower(trim($user->district));
            $reportQuery->whereRaw('LOWER(district) = ?', [$userDistrict]);
        }

        $totalReports = (clone $reportQuery)->count();
        $resolvedReports = (clone $reportQuery)->where('status', 'resolved')->count();
        $complianceRate = $totalReports > 0 ? round(($resolvedReports / $totalReports) * 100, 1) : 0;

        $redCount = (clone $reportQuery)->where('risk_level', 'red')->count();
        $yellowCount = (clone $reportQuery)->where('risk_level', 'yellow')->count();
        $riskIndexScore = min(100, round(($redCount * 25) + ($yellowCount * 10) + 15, 1));

        return view('monev.reports_builder', compact('totalReports', 'complianceRate', 'riskIndexScore'));
    }

    public function storeBuilder(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'period_name' => 'required|string|max:100',
            'target_region' => 'required|string|max:150',
            'total_target_reports' => 'required|integer|min:1',
            'total_realized_reports' => 'required|integer|min:0',
            'compliance_rate' => 'required|numeric|min:0|max:100',
            'risk_index_score' => 'required|numeric|min:0|max:100',
            'executive_summary' => 'required|string',
            'consultant_recommendations' => 'required|string',
        ]);

        $evalCode = 'MONEV-' . date('Y') . '-Q' . ceil(date('n')/3) . '-' . rand(100, 999);

        $eval = Evaluation::create([
            'evaluation_code' => $evalCode,
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'period_name' => $validated['period_name'],
            'target_region' => $validated['target_region'],
            'total_target_reports' => $validated['total_target_reports'],
            'total_realized_reports' => $validated['total_realized_reports'],
            'compliance_rate' => $validated['compliance_rate'],
            'risk_index_score' => $validated['risk_index_score'],
            'executive_summary' => $validated['executive_summary'],
            'consultant_recommendations' => $validated['consultant_recommendations'],
            'status' => 'published',
        ]);

        return redirect()->route('monev.index')
            ->with('success', 'Laporan Evaluasi e-Monev Pemda berhasil disusun dan dipublikasikan dengan Kode: ' . $evalCode);
    }

    public function printExecutive($id)
    {
        $evaluation = Evaluation::with('user')->findOrFail($id);

        $user = Auth::user();
        $reportQuery = Report::query();
        if ($user && $user->role !== 'vendor_admin' && !empty($user->district)) {
            $userDistrict = mb_strtolower(trim($user->district));
            $reportQuery->whereRaw('LOWER(district) = ?', [$userDistrict]);
        }

        $reports = (clone $reportQuery)->with('category')->latest()->get();
        $categories = Category::withCount(['reports' => function ($q) use ($user) {
            if ($user && $user->role !== 'vendor_admin' && !empty($user->district)) {
                $q->whereRaw('LOWER(district) = ?', [mb_strtolower(trim($user->district))]);
            }
        }])->get();

        $subdistrictStats = (clone $reportQuery)->select('subdistrict', 
                DB::raw('count(*) as total_reports'),
                DB::raw("sum(case when risk_level = 'red' then 1 else 0 end) as red_reports"),
                DB::raw("sum(case when status = 'resolved' then 1 else 0 end) as resolved_reports")
            )
            ->groupBy('subdistrict')
            ->get();

        return view('monev.print_executive', compact('evaluation', 'reports', 'categories', 'subdistrictStats'));
    }
}
