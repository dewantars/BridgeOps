<?php

namespace App\Http\Controllers;

use App\Models\EngineeringEvent;
use App\Models\Project;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = EngineeringEvent::with(['project', 'aiSummary'])->latest();

        if ($user->role === 'client') {
            // Only see events of projects the client has joined
            $query->whereHas('project.members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            
            // Only allow filtering by projects they are part of
            $projects = Project::whereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->orderBy('name')->get();
        } else {
            $projects = Project::orderBy('name')->get();
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('risk_level')) {
            $query->whereHas('aiSummary', fn($q) => $q->where('risk_level', $request->risk_level));
        }

        $activities = $query->paginate(20)->withQueryString();

        return view('activities.index', compact('activities', 'projects'));
    }

    public function show(EngineeringEvent $event)
    {
        $this->authorize('view-project', $event->project);

        $event->load(['project', 'aiSummary']);

        return view('activities.show', compact('event'));
    }
}
