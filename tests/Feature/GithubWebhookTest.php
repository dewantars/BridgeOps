<?php

namespace Tests\Feature;

use App\Jobs\GenerateAiSummaryJob;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GithubWebhookTest extends TestCase
{
    use RefreshDatabase;

    private string $secret = 'test-webhook-secret';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.github.webhook_secret' => $this->secret]);
    }

    private function makeSignedRequest(array $payload, string $eventType): \Illuminate\Testing\TestResponse
    {
        $body      = json_encode($payload);
        $signature = 'sha256=' . hash_hmac('sha256', $body, $this->secret);

        return $this->postJson('/api/webhooks/github', $payload, [
            'X-GitHub-Event'      => $eventType,
            'X-Hub-Signature-256' => $signature,
            'Content-Type'        => 'application/json',
        ]);
    }

    #[Test]
    public function webhook_returns_400_when_event_header_is_missing(): void
    {
        // Send a valid signature so the request passes signature check,
        // but omit X-GitHub-Event so we hit the 400 path.
        $payload   = ['repository' => ['name' => 'test-repo']];
        $body      = json_encode($payload);
        $signature = 'sha256=' . hash_hmac('sha256', $body, $this->secret);

        $this->postJson('/api/webhooks/github', $payload, [
            'X-Hub-Signature-256' => $signature,
        ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'Missing X-GitHub-Event header']);
    }

    #[Test]
    public function webhook_returns_403_with_invalid_signature(): void
    {
        $payload = ['repository' => ['name' => 'test-repo', 'full_name' => 'user/test-repo']];

        $this->postJson('/api/webhooks/github', $payload, [
            'X-GitHub-Event'      => 'push',
            'X-Hub-Signature-256' => 'sha256=invalid-signature',
        ])->assertStatus(403);
    }

    #[Test]
    public function push_webhook_with_valid_signature_creates_engineering_event(): void
    {
        Queue::fake();

        $project = Project::factory()->create(['github_repo_name' => 'bridgeops-test']);

        $payload = [
            'repository' => ['name' => 'bridgeops-test', 'full_name' => 'praktikan/bridgeops-test'],
            'pusher'     => ['name' => 'developer'],
            'ref'        => 'refs/heads/main',
            'head_commit' => [
                'id'      => 'abc123def456',
                'message' => 'feat: add new feature',
                'url'     => 'https://github.com/praktikan/bridgeops-test/commit/abc123',
            ],
            'commits' => [
                [
                    'id'      => 'abc123def456',
                    'message' => 'feat: add new feature',
                ],
            ],
        ];

        $this->makeSignedRequest($payload, 'push')
            ->assertOk()
            ->assertJsonFragment(['status' => 'ok']);

        $this->assertDatabaseHas('engineering_events', [
            'project_id' => $project->id,
            'event_type' => 'push',
            'source'     => 'github',
        ]);
    }

    #[Test]
    public function push_webhook_dispatches_ai_summary_job(): void
    {
        Queue::fake();

        Project::factory()->create(['github_repo_name' => 'bridgeops-test']);

        $payload = [
            'repository'  => ['name' => 'bridgeops-test', 'full_name' => 'praktikan/bridgeops-test'],
            'pusher'      => ['name' => 'developer'],
            'ref'         => 'refs/heads/main',
            'head_commit' => ['id' => 'abc123', 'message' => 'fix: bug fix'],
            'commits'     => [],
        ];

        $this->makeSignedRequest($payload, 'push');

        Queue::assertPushed(GenerateAiSummaryJob::class);
    }

    #[Test]
    public function webhook_returns_skipped_when_no_matching_project(): void
    {
        Queue::fake();

        $payload = [
            'repository'  => ['name' => 'nonexistent-repo', 'full_name' => 'user/nonexistent-repo'],
            'pusher'      => ['name' => 'developer'],
            'ref'         => 'refs/heads/main',
            'head_commit' => ['id' => 'abc123', 'message' => 'fix: bug'],
            'commits'     => [],
        ];

        $this->makeSignedRequest($payload, 'push')
            ->assertOk()
            ->assertJsonFragment(['status' => 'skipped']);

        Queue::assertNotPushed(GenerateAiSummaryJob::class);
    }

    #[Test]
    public function pull_request_webhook_creates_engineering_event(): void
    {
        Queue::fake();

        $project = Project::factory()->create(['github_repo_name' => 'bridgeops-pr-test']);

        $payload = [
            'repository'   => ['name' => 'bridgeops-pr-test', 'full_name' => 'praktikan/bridgeops-pr-test'],
            'action'       => 'opened',
            'sender'       => ['login' => 'dev-user'],
            'pull_request' => [
                'title'    => 'Add awesome feature',
                'body'     => 'This PR adds a great new feature.',
                'html_url' => 'https://github.com/praktikan/bridgeops-pr-test/pull/1',
                'head'     => ['ref' => 'feature/awesome'],
            ],
        ];

        $this->makeSignedRequest($payload, 'pull_request')
            ->assertOk()
            ->assertJsonFragment(['status' => 'ok']);

        $this->assertDatabaseHas('engineering_events', [
            'project_id' => $project->id,
            'event_type' => 'pull_request',
        ]);
    }

    #[Test]
    public function issues_webhook_creates_engineering_event_for_captured_action(): void
    {
        Queue::fake();

        $project = Project::factory()->create(['github_repo_name' => 'bridgeops-issue-test']);

        $payload = [
            'repository' => ['name' => 'bridgeops-issue-test', 'full_name' => 'praktikan/bridgeops-issue-test'],
            'action'     => 'opened',
            'sender'     => ['login' => 'reporter'],
            'issue'      => [
                'title'    => 'Bug: Login is broken',
                'body'     => 'Users cannot log in since the last deploy.',
                'html_url' => 'https://github.com/praktikan/bridgeops-issue-test/issues/5',
            ],
        ];

        $this->makeSignedRequest($payload, 'issues')
            ->assertOk()
            ->assertJsonFragment(['status' => 'ok']);

        $this->assertDatabaseHas('engineering_events', [
            'project_id' => $project->id,
            'event_type' => 'issue',
        ]);
    }

    #[Test]
    public function issues_webhook_returns_skipped_for_non_captured_action(): void
    {
        Queue::fake();

        $project = Project::factory()->create(['github_repo_name' => 'bridgeops-issue-test']);

        $payload = [
            'repository' => ['name' => 'bridgeops-issue-test', 'full_name' => 'praktikan/bridgeops-issue-test'],
            'action'     => 'pinned', // not a captured action
            'sender'     => ['login' => 'reporter'],
            'issue'      => [
                'title' => 'Some issue',
                'body'  => '',
            ],
        ];

        $this->makeSignedRequest($payload, 'issues')
            ->assertOk()
            ->assertJsonFragment(['status' => 'skipped']);

        Queue::assertNotPushed(GenerateAiSummaryJob::class);
    }
}
