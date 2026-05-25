<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class EngineeringEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id'  => Project::factory(),
            'source'      => $this->faker->randomElement(['github', 'manual']),
            'event_type'  => $this->faker->randomElement(['push', 'pull_request', 'issue', 'error_log']),
            'title'       => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'actor'       => $this->faker->userName(),
            'branch_name' => $this->faker->randomElement(['main', 'develop', 'feature/' . $this->faker->slug()]),
            'commit_hash' => substr($this->faker->sha1(), 0, 7),
            'github_url'  => 'https://github.com/example/repo/commit/' . $this->faker->sha1(),
            'raw_payload' => null,
        ];
    }

    public function push(): static
    {
        return $this->state(['event_type' => 'push', 'source' => 'github']);
    }

    public function issue(): static
    {
        return $this->state(['event_type' => 'issue', 'source' => 'github']);
    }

    public function pullRequest(): static
    {
        return $this->state(['event_type' => 'pull_request', 'source' => 'github']);
    }

    public function errorLog(): static
    {
        return $this->state(['event_type' => 'error_log', 'source' => 'manual']);
    }
}
