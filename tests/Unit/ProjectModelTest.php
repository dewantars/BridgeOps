<?php

namespace Tests\Unit;

use App\Models\Project;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProjectModelTest extends TestCase
{
    #[Test]
    public function status_label_returns_correct_value_for_on_track(): void
    {
        $project = new Project(['status' => 'on_track']);
        $this->assertEquals('On Track', $project->statusLabel());
    }

    #[Test]
    public function status_label_returns_correct_value_for_at_risk(): void
    {
        $project = new Project(['status' => 'at_risk']);
        $this->assertEquals('At Risk', $project->statusLabel());
    }

    #[Test]
    public function status_label_returns_correct_value_for_blocked(): void
    {
        $project = new Project(['status' => 'blocked']);
        $this->assertEquals('Blocked', $project->statusLabel());
    }

    #[Test]
    public function status_label_returns_correct_value_for_completed(): void
    {
        $project = new Project(['status' => 'completed']);
        $this->assertEquals('Completed', $project->statusLabel());
    }

    #[Test]
    public function status_color_returns_correct_color_for_each_status(): void
    {
        $this->assertEquals('green',  (new Project(['status' => 'on_track']))->statusColor());
        $this->assertEquals('yellow', (new Project(['status' => 'at_risk']))->statusColor());
        $this->assertEquals('red',    (new Project(['status' => 'blocked']))->statusColor());
        $this->assertEquals('blue',   (new Project(['status' => 'completed']))->statusColor());
        $this->assertEquals('gray',   (new Project(['status' => 'unknown']))->statusColor());
    }
}
