<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateAiSummaryJob;
use App\Services\GithubWebhookService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function github(Request $request, GithubWebhookService $webhookService)
    {
        $secret = config('services.github.webhook_secret', '');

        // Verify HMAC signature (skip if no secret set for local dev)
        if (!empty($secret) && $secret !== 'your_webhook_secret_here') {
            if (!$webhookService->verifySignature($request, $secret)) {
                Log::warning('GitHub webhook: invalid signature');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $eventType = $request->header('X-GitHub-Event');
        $payload   = $request->json()->all();

        if (!$eventType) {
            return response()->json(['error' => 'Missing X-GitHub-Event header'], 400);
        }

        Log::info("GitHub webhook received: {$eventType}", [
            'repo' => $payload['repository']['name'] ?? 'unknown',
        ]);

        $event = $webhookService->handle($eventType, $payload);

        if ($event) {
            GenerateAiSummaryJob::dispatch($event);
            return response()->json([
                'status'   => 'ok',
                'message'  => 'Event processed and AI summary queued',
                'event_id' => $event->id,
            ]);
        }

        return response()->json([
            'status'  => 'skipped',
            'message' => "Event type '{$eventType}' not handled or no matching project",
        ]);
    }
}
