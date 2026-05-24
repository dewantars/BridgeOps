<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Report;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Project $project)
    {
        $reports = $project->reports()->with('generatedBy')->latest()->paginate(10);

        return view('reports.index', compact('project', 'reports'));
    }

    public function show(Project $project, Report $report)
    {
        return view('reports.show', compact('project', 'report'));
    }

    public function generate(Project $project, GeminiService $gemini)
    {
        $this->authorize('manage-projects');

        $project->load(['engineeringEvents.aiSummary', 'manualErrorLogs']);

        $recentEvents = $project->engineeringEvents()
            ->latest()
            ->take(20)
            ->get()
            ->map(fn($e) => [
                'event_type' => $e->event_type,
                'title'      => $e->title,
                'actor'      => $e->actor,
                'created_at' => $e->created_at->format('Y-m-d'),
            ])->toArray();

        $errorLogs = $project->manualErrorLogs()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($e) => [
                'title'    => $e->title,
                'severity' => $e->severity,
            ])->toArray();

        $projectData = [
            'name'          => $project->name,
            'client_name'   => $project->client_name,
            'status'        => $project->statusLabel(),
            'start_date'    => $project->start_date?->format('Y-m-d') ?? 'N/A',
            'end_date'      => $project->end_date?->format('Y-m-d') ?? 'N/A',
            'recent_events' => $recentEvents,
            'error_logs'    => $errorLogs,
        ];

        $content = $gemini->generateProjectReport($projectData);

        $report = Report::create([
            'project_id'   => $project->id,
            'title'        => "Project Summary — " . now()->format('d M Y'),
            'report_type'  => 'project_summary',
            'content'      => $content,
            'generated_by' => auth()->id(),
        ]);

        return redirect()->route('reports.show', [$project, $report])
            ->with('success', 'Laporan AI berhasil di-generate!');
    }
}
