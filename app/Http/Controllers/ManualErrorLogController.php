<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateAiSummaryJob;
use App\Models\EngineeringEvent;
use App\Models\ManualErrorLog;
use App\Models\Project;
use Illuminate\Http\Request;

class ManualErrorLogController extends Controller
{
    public function create()
    {
        $this->authorize('manage-projects');
        $projects = Project::orderBy('name')->get();

        return view('errors.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage-projects');

        $validated = $request->validate([
            'project_id'    => 'required|exists:projects,id',
            'title'         => 'required|string|max:255',
            'environment'   => 'nullable|string|max:100',
            'error_message' => 'required|string',
            'stack_trace'   => 'nullable|string',
            'severity'      => 'required|in:low,medium,high,critical',
            'notes'         => 'nullable|string',
        ]);

        // 1. Save to manual_error_logs
        $errorLog = ManualErrorLog::create($validated);

        // 2. Create corresponding engineering event
        $event = EngineeringEvent::create([
            'project_id'  => $validated['project_id'],
            'source'      => 'manual',
            'event_type'  => 'error_log',
            'title'       => "[{$errorLog->severityLabel()}] {$errorLog->title}",
            'description' => "Environment: {$errorLog->environment}\n\nError: {$errorLog->error_message}\n\nStack Trace:\n{$errorLog->stack_trace}",
            'actor'       => auth()->user()->name,
        ]);

        // 3. Dispatch AI summary job
        GenerateAiSummaryJob::dispatch($event);

        return redirect()->route('projects.show', $validated['project_id'])
            ->with('success', 'Error log berhasil disimpan dan sedang diproses oleh AI.');
    }
}
