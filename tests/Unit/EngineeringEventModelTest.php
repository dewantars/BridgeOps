<?php

namespace Tests\Unit;

use App\Models\EngineeringEvent;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EngineeringEventModelTest extends TestCase
{
    #[Test]
    public function event_type_label_returns_correct_label(): void
    {
        $this->assertEquals('Push',         (new EngineeringEvent(['event_type' => 'push']))->eventTypeLabel());
        $this->assertEquals('Pull Request',  (new EngineeringEvent(['event_type' => 'pull_request']))->eventTypeLabel());
        $this->assertEquals('Issue',         (new EngineeringEvent(['event_type' => 'issue']))->eventTypeLabel());
        $this->assertEquals('Error Log',     (new EngineeringEvent(['event_type' => 'error_log']))->eventTypeLabel());
        $this->assertEquals('Custom',        (new EngineeringEvent(['event_type' => 'custom']))->eventTypeLabel());
    }

    #[Test]
    public function source_label_returns_correct_label(): void
    {
        $this->assertEquals('GitHub', (new EngineeringEvent(['source' => 'github']))->sourceLabel());
        $this->assertEquals('Manual', (new EngineeringEvent(['source' => 'manual']))->sourceLabel());
        $this->assertEquals('Api',    (new EngineeringEvent(['source' => 'api']))->sourceLabel());
    }

    #[Test]
    public function risk_color_defaults_to_green_when_no_summary(): void
    {
        $event = new EngineeringEvent(['event_type' => 'push']);
        $this->assertEquals('green', $event->riskColor());
    }
}
