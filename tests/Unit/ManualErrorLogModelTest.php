<?php

namespace Tests\Unit;

use App\Models\ManualErrorLog;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ManualErrorLogModelTest extends TestCase
{
    #[Test]
    public function severity_label_returns_correct_values(): void
    {
        $this->assertEquals('Low',      (new ManualErrorLog(['severity' => 'low']))->severityLabel());
        $this->assertEquals('Medium',   (new ManualErrorLog(['severity' => 'medium']))->severityLabel());
        $this->assertEquals('High',     (new ManualErrorLog(['severity' => 'high']))->severityLabel());
        $this->assertEquals('Critical', (new ManualErrorLog(['severity' => 'critical']))->severityLabel());
    }

    #[Test]
    public function severity_color_returns_correct_colors(): void
    {
        $this->assertEquals('green',  (new ManualErrorLog(['severity' => 'low']))->severityColor());
        $this->assertEquals('yellow', (new ManualErrorLog(['severity' => 'medium']))->severityColor());
        $this->assertEquals('orange', (new ManualErrorLog(['severity' => 'high']))->severityColor());
        $this->assertEquals('red',    (new ManualErrorLog(['severity' => 'critical']))->severityColor());
        $this->assertEquals('gray',   (new ManualErrorLog(['severity' => 'unknown']))->severityColor());
    }
}
