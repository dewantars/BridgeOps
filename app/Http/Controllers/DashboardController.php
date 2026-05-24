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
        $user = auth()->user();

        if ($user->role === 'client') {
            // Client: only see projects they are members of
            $projectIdsQuery = Project::whereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            $projectIds = (clone $projectIdsQuery)->pluck('id');

            $totalProjects   = $projectIdsQuery->count();
            $totalActivities = EngineeringEvent::whereIn('project_id', $projectIds)->count();
            $totalIssues     = EngineeringEvent::whereIn('project_id', $projectIds)->where('event_type', 'issue')->count();
            $totalErrorLogs  = ManualErrorLog::whereIn('project_id', $projectIds)->count();

            $highRiskCount = \App\Models\AiSummary::whereHas('engineeringEvent', fn($q) => $q->whereIn('project_id', $projectIds))
                ->whereIn('risk_level', ['high', 'critical'])
                ->count();

            $recentActivities = EngineeringEvent::with(['project', 'aiSummary'])
                ->whereIn('project_id', $projectIds)
                ->latest()
                ->take(10)
                ->get();

            $projectsByStatus = Project::whereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
        } else {
            // Admin/PM: see all
            $totalProjects   = Project::count();
            $totalActivities = EngineeringEvent::count();
            $totalIssues     = EngineeringEvent::where('event_type', 'issue')->count();
            $totalErrorLogs  = ManualErrorLog::count();

            $highRiskCount = \App\Models\AiSummary::whereIn('risk_level', ['high', 'critical'])->count();

            $recentActivities = EngineeringEvent::with(['project', 'aiSummary'])
                ->latest()
                ->take(10)
                ->get();

            $projectsByStatus = Project::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
        }

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
