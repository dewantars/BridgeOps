<?php

namespace App\Services;

use App\Models\EngineeringEvent;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GithubWebhookService
{
    public function handle(string $eventType, array $payload): ?EngineeringEvent
    {
        // Find project by repository name
        $repoName = $payload['repository']['name'] ?? null;
        $repoFullName = $payload['repository']['full_name'] ?? null;

        if (!$repoName) {
            Log::warning('GitHub webhook: no repository name in payload');
            return null;
        }

        $project = Project::where('github_repo_name', $repoName)
            ->orWhere('github_repo_name', $repoFullName)
            ->first();

        if (!$project) {
            Log::info("GitHub webhook: no project found for repo '{$repoName}'");
            return null;
        }

        return match ($eventType) {
            'push'         => $this->handlePush($project, $payload),
            'pull_request' => $this->handlePullRequest($project, $payload),
            'issues'       => $this->handleIssues($project, $payload),
            default        => null,
        };
    }

    private function handlePush(Project $project, array $payload): EngineeringEvent
    {
        $pusher    = $payload['pusher']['name'] ?? 'Unknown';
        $ref       = $payload['ref'] ?? 'refs/heads/main';
        $branch    = str_replace('refs/heads/', '', $ref);
        $headCommit = $payload['head_commit'] ?? [];
        $commitHash = substr($headCommit['id'] ?? '', 0, 7);
        $commitMsg  = $headCommit['message'] ?? '';

        // Build description from commits
        $commits    = $payload['commits'] ?? [];
        $commitList = collect($commits)->map(fn($c) => "- " . substr($c['id'], 0, 7) . ": " . $c['message'])->join("\n");

        return EngineeringEvent::create([
            'project_id'  => $project->id,
            'source'      => 'github',
            'event_type'  => 'push',
            'title'       => "Push by {$pusher} to {$branch}" . ($commitHash ? " [{$commitHash}]" : ''),
            'description' => $commitMsg . ($commitList ? "\n\nCommits:\n{$commitList}" : ''),
            'actor'       => $pusher,
            'branch_name' => $branch,
            'commit_hash' => $commitHash,
            'github_url'  => $headCommit['url'] ?? null,
            'raw_payload' => $payload,
        ]);
    }

    private function handlePullRequest(Project $project, array $payload): EngineeringEvent
    {
        $pr     = $payload['pull_request'] ?? [];
        $action = $payload['action'] ?? 'opened';
        $sender = $payload['sender']['login'] ?? 'Unknown';
        $title  = $pr['title'] ?? 'Untitled PR';
        $branch = $pr['head']['ref'] ?? null;

        return EngineeringEvent::create([
            'project_id'  => $project->id,
            'source'      => 'github',
            'event_type'  => 'pull_request',
            'title'       => "PR {$action}: {$title}",
            'description' => $pr['body'] ?? '',
            'actor'       => $sender,
            'branch_name' => $branch,
            'github_url'  => $pr['html_url'] ?? null,
            'raw_payload' => $payload,
        ]);
    }

    private function handleIssues(Project $project, array $payload): ?EngineeringEvent
    {
        $action  = $payload['action'] ?? 'opened';
        $issue   = $payload['issue'] ?? [];
        $sender  = $payload['sender']['login'] ?? 'Unknown';
        $title   = $issue['title'] ?? 'Untitled Issue';

        // Only capture relevant actions
        $capturedActions = ['opened', 'closed', 'reopened', 'edited', 'assigned'];
        if (!in_array($action, $capturedActions)) {
            return null;
        }

        return EngineeringEvent::create([
            'project_id'  => $project->id,
            'source'      => 'github',
            'event_type'  => 'issue',
            'title'       => "Issue {$action}: {$title}",
            'description' => $issue['body'] ?? '',
            'actor'       => $sender,
            'github_url'  => $issue['html_url'] ?? null,
            'raw_payload' => $payload,
        ]);
    }

    public function verifySignature(Request $request, string $secret): bool
    {
        $signature = $request->header('X-Hub-Signature-256');

        if (!$signature) {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        return hash_equals($expected, $signature);
    }
}
