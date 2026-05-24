<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Project;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Show the list of all project chats the user is involved in.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->canManageProjects()) {
            // Admins/PMs see all projects with chat activity
            $projects = Project::withCount(['chatMessages as unread_count' => function ($q) {
                    $q->where('is_read', false)
                      ->where('sender_role', 'client');
                }])
                ->with(['chatMessages' => function ($q) {
                    $q->latest()->limit(1);
                }])
                ->orderByDesc('updated_at')
                ->get();
        } else {
            // Clients see only projects they are members of
            $projects = $user->projects()
                ->withCount(['chatMessages as unread_count' => function ($q) use ($user) {
                    $q->where('is_read', false)
                      ->whereNotIn('sender_role', ['client']);
                }])
                ->with(['chatMessages' => function ($q) {
                    $q->latest()->limit(1);
                }])
                ->orderByDesc('updated_at')
                ->get();
        }

        return view('chat.index', compact('projects'));
    }

    /**
     * Show the chat room for a specific project.
     */
    public function show(Project $project)
    {
        $user = auth()->user();

        // Authorization: only members or admins/pms
        if (!$user->canManageProjects()) {
            $isMember = $project->members()->where('user_id', $user->id)->exists();
            if (!$isMember) {
                abort(403, 'You do not have access to this project chat.');
            }
        }

        // Load all messages for this project
        $messages = ChatMessage::with('sender')
            ->where('project_id', $project->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages from the other party as read
        ChatMessage::where('project_id', $project->id)
            ->where('is_read', false)
            ->where('sender_id', '!=', $user->id)
            ->update(['is_read' => true]);

        return view('chat.show', compact('project', 'messages'));
    }

    /**
     * Store a new chat message.
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();

        // Authorization check
        if (!$user->canManageProjects()) {
            $isMember = $project->members()->where('user_id', $user->id)->exists();
            if (!$isMember) {
                abort(403);
            }
        }

        $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        ChatMessage::create([
            'project_id'  => $project->id,
            'sender_id'   => $user->id,
            'body'        => $request->input('body'),
            'sender_role' => $user->role,
            'is_read'     => false,
        ]);

        return redirect()->route('chat.show', $project)->with('success', 'Pesan terkirim.');
    }

    /**
     * Return unread message count as JSON (for badge polling).
     */
    public function unreadCount()
    {
        $user = auth()->user();

        if ($user->canManageProjects()) {
            $count = ChatMessage::where('is_read', false)
                ->where('sender_role', 'client')
                ->count();
        } else {
            $projectIds = $user->projects()->pluck('projects.id');
            $count = ChatMessage::whereIn('project_id', $projectIds)
                ->where('is_read', false)
                ->whereNotIn('sender_role', ['client'])
                ->count();
        }

        return response()->json(['count' => $count]);
    }
}
