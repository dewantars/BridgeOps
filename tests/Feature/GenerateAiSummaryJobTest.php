<?php

namespace Tests\Feature;

use App\Jobs\GenerateAiSummaryJob;
use App\Models\AiSummary;
use App\Models\EngineeringEvent;
use App\Models\Project;
use App\Services\GeminiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GenerateAiSummaryJobTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function job_can_be_pushed_to_queue(): void
    {
        Queue::fake();

        $project = Project::factory()->create();
        $event   = EngineeringEvent::factory()->create(['project_id' => $project->id]);

        GenerateAiSummaryJob::dispatch($event);

        Queue::assertPushed(GenerateAiSummaryJob::class, function ($job) use ($event) {
            return $job->event->id === $event->id;
        });
    }

    #[Test]
    public function job_creates_ai_summary_using_mock_gemini_service(): void
    {
        // Mock GeminiService to return a predictable response
        $this->mock(GeminiService::class, function ($mock) {
            $mock->shouldReceive('generateSummary')
                ->once()
                ->andReturn([
                    'technical_summary'       => 'Mock technical summary.',
                    'business_summary'        => 'Mock business summary.',
                    'client_friendly_summary' => 'Mock client summary.',
                    'risk_level'              => 'low',
                    'business_impact'         => 'Minimal impact.',
                    'recommended_action'      => 'No action needed.',
                ]);
        });

        $project = Project::factory()->create();
        $event   = EngineeringEvent::factory()->create(['project_id' => $project->id]);

        // Run the job synchronously
        (new GenerateAiSummaryJob($event))->handle(app(GeminiService::class));

        $this->assertDatabaseHas('ai_summaries', [
            'engineering_event_id' => $event->id,
            'risk_level'           => 'low',
        ]);
    }

    #[Test]
    public function job_updates_existing_ai_summary_on_rerun(): void
    {
        $this->mock(GeminiService::class, function ($mock) {
            $mock->shouldReceive('generateSummary')
                ->andReturn([
                    'technical_summary'       => 'Updated technical summary.',
                    'business_summary'        => 'Updated business summary.',
                    'client_friendly_summary' => 'Updated client summary.',
                    'risk_level'              => 'high',
                    'business_impact'         => 'Significant impact.',
                    'recommended_action'      => 'Immediate review required.',
                ]);
        });

        $project = Project::factory()->create();
        $event   = EngineeringEvent::factory()->create(['project_id' => $project->id]);

        // Create initial summary
        AiSummary::create([
            'engineering_event_id' => $event->id,
            'technical_summary'    => 'Old summary.',
            'business_summary'     => 'Old business.',
            'client_friendly_summary' => 'Old client.',
            'risk_level'           => 'low',
            'business_impact'      => 'Minimal.',
            'recommended_action'   => 'None.',
        ]);

        // Run job again (should update, not create duplicate)
        (new GenerateAiSummaryJob($event))->handle(app(GeminiService::class));

        $this->assertDatabaseCount('ai_summaries', 1);
        $this->assertDatabaseHas('ai_summaries', [
            'engineering_event_id' => $event->id,
            'risk_level'           => 'high',
            'technical_summary'    => 'Updated technical summary.',
        ]);
    }
}
