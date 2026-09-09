<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Report::with(['category', 'user']);

        // Multi-tenant District Isolation (Isolasi Wilayah Kabupaten/Kota)
        if ($user && $user->role !== 'vendor_admin' && !empty($user->district)) {
            $userDistrict = mb_strtolower(trim($user->district));
            $query->whereRaw('LOWER(district) = ?', [$userDistrict]);
        } elseif ($request->filled('district')) {
            $reqDistrict = mb_strtolower(trim($request->district));
            $query->whereRaw('LOWER(district) = ?', [$reqDistrict]);
        }

        // Search & Filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('report_number', 'like', '%' . $request->search . '%')
                  ->orWhere('subdistrict', 'like', '%' . $request->search . '%')
                  ->orWhere('village', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = (clone $query)->latest()->paginate(10);

        // Stats calculation with district scope
        $baseQuery = Report::query();
        if ($user && $user->role !== 'vendor_admin' && !empty($user->district)) {
            $userDistrict = mb_strtolower(trim($user->district));
            $baseQuery->whereRaw('LOWER(district) = ?', [$userDistrict]);
        } elseif ($request->filled('district')) {
            $reqDistrict = mb_strtolower(trim($request->district));
            $baseQuery->whereRaw('LOWER(district) = ?', [$reqDistrict]);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'resolved' => (clone $baseQuery)->where('status', 'resolved')->count(),
            'red_alerts' => (clone $baseQuery)->where('risk_level', 'red')->count(),
            'yellow_alerts' => (clone $baseQuery)->where('risk_level', 'yellow')->count(),
            'green_alerts' => (clone $baseQuery)->where('risk_level', 'green')->count(),
        ];

        // Geo map data (Reports with coordinates)
        $mapReports = (clone $baseQuery)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('category')
            ->get();

        // Chart data by Category
        $categories = Category::all();
        $chartCategoryLabels = [];
        $chartCategoryData = [];

        foreach ($categories as $cat) {
            $chartCategoryLabels[] = $cat->name;
            $chartCategoryData[] = (clone $baseQuery)->where('category_id', $cat->id)->count();
        }

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

        return view('dashboard.index', compact(
            'reports',
            'stats',
            'mapReports',
            'categories',
            'chartCategoryLabels',
            'chartCategoryData',
            'districts'
        ));
    }
}
