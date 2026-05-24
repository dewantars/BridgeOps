<?php

namespace App\Http\Controllers;

use App\Models\EngineeringEvent;
use App\Models\ManualErrorLog;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects  = Project::count();
        $totalActivities = EngineeringEvent::count();
        $totalIssues    = EngineeringEvent::where('event_type', 'issue')->count();
        $totalErrorLogs = ManualErrorLog::count();

        $highRiskCount = \App\Models\AiSummary::whereIn('risk_level', ['high', 'critical'])->count();

        $recentActivities = EngineeringEvent::with(['project', 'aiSummary'])
            ->latest()
            ->take(10)
            ->get();

        $projectsByStatus = Project::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard', compact(
            'totalProjects',
            'totalActivities',
            'totalIssues',
            'totalErrorLogs',
            'highRiskCount',
            'recentActivities',
            'projectsByStatus'
        ));
    }
}
