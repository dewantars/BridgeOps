<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'engineering_event_id',
        'technical_summary',
        'business_summary',
        'client_friendly_summary',
        'risk_level',
        'business_impact',
        'recommended_action',
    ];

    public function riskBadgeColor(): string
    {
        return match ($this->risk_level) {
            'low'      => 'green',
            'medium'   => 'yellow',
            'high'     => 'orange',
            'critical' => 'red',
            default    => 'gray',
        };
    }

    public function riskLabel(): string
    {
        return match ($this->risk_level) {
            'low'      => 'Low Risk',
            'medium'   => 'Medium Risk',
            'high'     => 'High Risk',
            'critical' => 'Critical',
            default    => ucfirst($this->risk_level),
        };
    }

    // Relationships
    public function engineeringEvent()
    {
        return $this->belongsTo(EngineeringEvent::class);
    }
}
