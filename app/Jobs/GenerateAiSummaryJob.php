<?php

namespace App\Jobs;

use App\Models\AiSummary;
use App\Models\EngineeringEvent;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAiSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    public function __construct(public EngineeringEvent $event) {}

    public function handle(GeminiService $gemini): void
    {
        Log::info("GenerateAiSummaryJob: processing event #{$this->event->id} ({$this->event->event_type})");

        $result = $gemini->generateSummary($this->event);

        AiSummary::updateOrCreate(
            ['engineering_event_id' => $this->event->id],
            $result
        );

        Log::info("GenerateAiSummaryJob: summary saved for event #{$this->event->id}, risk={$result['risk_level']}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("GenerateAiSummaryJob failed for event #{$this->event->id}: " . $exception->getMessage());
    }
}
