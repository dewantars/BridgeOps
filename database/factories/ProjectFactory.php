<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'             => $this->faker->company() . ' Project',
            'client_name'      => $this->faker->company(),
            'description'      => $this->faker->paragraph(),
            'repository_url'   => 'https://github.com/' . $this->faker->slug() . '/' . $this->faker->slug(),
            'github_repo_name' => $this->faker->slug(),
            'status'           => $this->faker->randomElement(['on_track', 'at_risk', 'blocked', 'completed']),
            'start_date'       => $this->faker->dateTimeBetween('-3 months', 'now'),
            'end_date'         => $this->faker->dateTimeBetween('now', '+3 months'),
        ];
    }

    public function onTrack(): static
    {
        return $this->state(['status' => 'on_track']);
    }

    public function completed(): static
    {
        return $this->state(['status' => 'completed']);
    }
}
