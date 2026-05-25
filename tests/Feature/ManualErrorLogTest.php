<?php

namespace Tests\Feature;

use App\Jobs\GenerateAiSummaryJob;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ManualErrorLogTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pm;
    private User $client;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin   = User::factory()->create(['role' => 'admin']);
        $this->pm      = User::factory()->create(['role' => 'pm']);
        $this->client  = User::factory()->create(['role' => 'client']);
        $this->project = Project::factory()->create();
    }

    #[Test]
    public function admin_can_access_create_error_log_page(): void
    {
        $this->actingAs($this->admin)
            ->get('/manual-errors/create')
            ->assertOk()
            ->assertViewIs('errors.create');
    }

    #[Test]
    public function client_cannot_access_create_error_log_page(): void
    {
        $this->actingAs($this->client)
            ->get('/manual-errors/create')
            ->assertForbidden();
    }

    #[Test]
    public function admin_can_submit_a_manual_error_log(): void
    {
        Queue::fake();

        $this->actingAs($this->admin)
            ->post('/manual-errors', [
                'project_id'    => $this->project->id,
                'title'         => 'Payment Gateway Timeout',
                'environment'   => 'production',
                'error_message' => 'Connection timeout after 30 seconds',
                'severity'      => 'critical',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('manual_error_logs', [
            'title'    => 'Payment Gateway Timeout',
            'severity' => 'critical',
        ]);
    }

    #[Test]
    public function submitting_error_log_creates_an_engineering_event(): void
    {
        Queue::fake();

        $this->actingAs($this->admin)
            ->post('/manual-errors', [
                'project_id'    => $this->project->id,
                'title'         => 'Database Connection Error',
                'error_message' => 'SQLSTATE[HY000]: General error',
                'severity'      => 'high',
            ]);

        $this->assertDatabaseHas('engineering_events', [
            'project_id' => $this->project->id,
            'source'     => 'manual',
            'event_type' => 'error_log',
        ]);
    }

    #[Test]
    public function submitting_error_log_dispatches_ai_summary_job(): void
    {
        Queue::fake();

        $this->actingAs($this->admin)
            ->post('/manual-errors', [
                'project_id'    => $this->project->id,
                'title'         => 'Queue Failure',
                'error_message' => 'Job failed after 3 retries',
                'severity'      => 'medium',
            ]);

        Queue::assertPushed(GenerateAiSummaryJob::class);
    }

    #[Test]
    public function client_cannot_submit_an_error_log(): void
    {
        $this->actingAs($this->client)
            ->post('/manual-errors', [
                'project_id'    => $this->project->id,
                'title'         => 'Unauthorized Error Log',
                'error_message' => 'Some error',
                'severity'      => 'low',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('manual_error_logs', ['title' => 'Unauthorized Error Log']);
    }

    #[Test]
    public function error_log_submission_requires_mandatory_fields(): void
    {
        $this->actingAs($this->admin)
            ->post('/manual-errors', [])
            ->assertSessionHasErrors(['project_id', 'title', 'error_message', 'severity']);
    }

    #[Test]
    public function error_log_submission_validates_severity_enum(): void
    {
        $this->actingAs($this->admin)
            ->post('/manual-errors', [
                'project_id'    => $this->project->id,
                'title'         => 'Test',
                'error_message' => 'An error',
                'severity'      => 'catastrophic', // invalid value
            ])
            ->assertSessionHasErrors(['severity']);
    }
}
