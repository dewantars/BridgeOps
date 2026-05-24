<?php

namespace App\Http\Controllers;

use App\Models\EngineeringEvent;
use App\Models\Project;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = EngineeringEvent::with(['project', 'aiSummary'])->latest();

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
        $projects   = Project::orderBy('name')->get();

        return view('activities.index', compact('activities', 'projects'));
    }

    public function show(EngineeringEvent $event)
    {
        $event->load(['project', 'aiSummary']);

        return view('activities.show', compact('event'));
    }
}
