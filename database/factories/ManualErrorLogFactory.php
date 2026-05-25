<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManualErrorLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id'    => Project::factory(),
            'title'         => $this->faker->sentence(),
            'environment'   => $this->faker->randomElement(['production', 'staging', 'development']),
            'error_message' => 'Error ' . $this->faker->numerify('###') . ': ' . $this->faker->sentence(),
            'stack_trace'   => $this->faker->text(500),
            'severity'      => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'notes'         => $this->faker->paragraph(),
        ];
    }

    public function critical(): static
    {
        return $this->state(['severity' => 'critical']);
    }

    public function low(): static
    {
        return $this->state(['severity' => 'low']);
    }
}
