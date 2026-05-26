<?php

use App\Http\Controllers\WebhookController;
use App\Models\EngineeringEvent;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// GitHub Webhook - excluded from CSRF via bootstrap/app.php
Route::post('/webhooks/github', [WebhookController::class, 'github']);

// Test Gemini API Summary via Postman - ONLY available in local development
if (app()->environment('local')) {
    Route::post('/test-gemini', function (Request $request, GeminiService $gemini) {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'event_type' => 'required|string|in:push,pull_request,issue,error_log',
            'actor' => 'required|string',
            'source' => 'nullable|string',
        ]);

        // Membuat instance model temporer tanpa menyimpannya ke database
        $event = new EngineeringEvent([
            'source' => $request->input('source', 'manual'),
            'event_type' => $request->input('event_type'),
            'title' => $request->input('title'),
            'description' => $request->input('description', ''),
            'actor' => $request->input('actor'),
        ]);

        $startTime = microtime(true);
        $summary = $gemini->generateSummary($event);
        $duration = round(microtime(true) - $startTime, 2);

        return response()->json([
            'status' => 'success',
            'duration_seconds' => $duration,
            'gemini_api_key_configured' => !empty(config('services.gemini.api_key')),
            'is_mock_fallback' => str_starts_with($summary['technical_summary'] ?? '', '[MOCK]'),
            'data' => $summary
        ]);
    });
}

