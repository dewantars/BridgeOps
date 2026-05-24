<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManualErrorLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Project Members
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('project-members.store');
    Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])->name('project-members.destroy');

    // Activity Timeline
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/{event}', [ActivityController::class, 'show'])->name('activities.show');

    // Manual Error Logs
    Route::get('/manual-errors/create', [ManualErrorLogController::class, 'create'])->name('manual-errors.create');
    Route::post('/manual-errors', [ManualErrorLogController::class, 'store'])->name('manual-errors.store');

    // Reports
    Route::post('/projects/{project}/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/projects/{project}/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/projects/{project}/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread-count');
    Route::get('/chat/{project}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{project}', [ChatController::class, 'store'])->name('chat.store');
});

require __DIR__ . '/auth.php';
