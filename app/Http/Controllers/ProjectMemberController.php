<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    /**
     * Add a user (client) to a project.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('manage-projects');

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($request->user_id);

        // Prevent double-adding
        if ($project->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', "Pengguna \"{$user->name}\" sudah menjadi anggota proyek ini.");
        }

        $project->members()->attach($user->id, ['role' => $user->role]);

        return back()->with('success', "Pengguna \"{$user->name}\" berhasil ditambahkan ke proyek.");
    }

    /**
     * Remove a user from a project.
     */
    public function destroy(Project $project, User $user)
    {
        $this->authorize('manage-projects');

        // Don't allow removing yourself if you're the only admin/pm
        $remainingManagers = $project->members()
            ->whereIn('project_members.role', ['admin', 'pm'])
            ->where('users.id', '!=', $user->id)
            ->count();

        if (auth()->id() === $user->id && $remainingManagers === 0) {
            return back()->with('error', 'Anda tidak dapat menghapus diri sendiri karena tidak ada admin/PM lain di proyek ini.');
        }

        $project->members()->detach($user->id);

        return back()->with('success', "Pengguna \"{$user->name}\" berhasil dihapus dari proyek.");
    }
}
