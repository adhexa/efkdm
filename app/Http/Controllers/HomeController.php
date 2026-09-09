<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('reports')->get();

        $stats = [
            'total_reports' => Report::count(),
            'red_alerts' => Report::where('risk_level', 'red')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
        ];

        return view('landing', compact('categories', 'stats'));
    }
}
