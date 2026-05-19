<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        $stats = $this->dashboardService->getStats();
        $chartData = $this->dashboardService->getChartData();
        $recentActivities = $this->dashboardService->getRecentActivities();

        return view('dashboard.index', compact('stats', 'chartData', 'recentActivities'));
    }
}
