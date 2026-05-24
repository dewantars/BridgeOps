<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('engineeringEvents')
            ->latest()
            ->paginate(12);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('manage-projects');
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage-projects');

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'client_name'       => 'required|string|max:255',
            'description'       => 'nullable|string',
            'repository_url'    => 'nullable|url|max:500',
            'github_repo_name'  => 'nullable|string|max:255',
            'status'            => 'required|in:on_track,at_risk,blocked,completed',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create($validated);

        // Auto-add creator as PM member
        $project->members()->attach(Auth::id(), ['role' => Auth::user()->role]);

        return redirect()->route('projects.show', $project)
            ->with('success', "Project \"{$project->name}\" berhasil dibuat.");
    }

    public function show(Project $project)
    {
        $project->load([
            'engineeringEvents' => fn($q) => $q->with('aiSummary')->latest()->take(20),
            'manualErrorLogs'   => fn($q) => $q->latest()->take(10),
            'reports'           => fn($q) => $q->with('generatedBy')->latest()->take(5),
            'members',
        ]);

        $issueEvents = $project->engineeringEvents()
            ->where('event_type', 'issue')
            ->with('aiSummary')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_activities' => $project->engineeringEvents()->count(),
            'open_issues'      => $project->engineeringEvents()
                ->where('event_type', 'issue')
                ->where('title', 'like', '%opened%')
                ->count(),
            'error_logs'       => $project->manualErrorLogs()->count(),
            'high_risk'        => \App\Models\AiSummary::whereHas('engineeringEvent', fn($q) => $q->where('project_id', $project->id))
                ->whereIn('risk_level', ['high', 'critical'])
                ->count(),
        ];

        // Users not yet a member of this project (for the add-member dropdown)
        $memberIds    = $project->members->pluck('id');
        $nonMembers   = User::whereNotIn('id', $memberIds)->orderBy('name')->get();

        return view('projects.show', compact('project', 'issueEvents', 'stats', 'nonMembers'));
    }

    public function edit(Project $project)
    {
        $this->authorize('manage-projects');
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('manage-projects');

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'client_name'       => 'required|string|max:255',
            'description'       => 'nullable|string',
            'repository_url'    => 'nullable|url|max:500',
            'github_repo_name'  => 'nullable|string|max:255',
            'status'            => 'required|in:on_track,at_risk,blocked,completed',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', "Project \"{$project->name}\" berhasil diperbarui.");
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage-projects');

        $name = $project->name;
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', "Project \"{$name}\" berhasil dihapus.");
    }
}
