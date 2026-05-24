<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define gate for project management (admin & pm only)
        Gate::define('manage-projects', function ($user) {
            return in_array($user->role, ['admin', 'pm']);
        });

        // Define gate for viewing specific project (admin/pm can see all, client must be a member)
        Gate::define('view-project', function ($user, \App\Models\Project $project) {
            if (in_array($user->role, ['admin', 'pm'])) {
                return true;
            }
            return $project->members()->where('users.id', $user->id)->exists();
        });

        // Admin can do everything
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}
