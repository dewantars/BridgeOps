<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineeringEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'source',
        'event_type',
        'title',
        'description',
        'actor',
        'branch_name',
        'commit_hash',
        'github_url',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];

    // Event type label helpers
    public function eventTypeLabel(): string
    {
        return match ($this->event_type) {
            'push'         => 'Push',
            'pull_request' => 'Pull Request',
            'issue'        => 'Issue',
            'error_log'    => 'Error Log',
            default        => ucfirst($this->event_type),
        };
    }

    public function sourceLabel(): string
    {
        return match ($this->source) {
            'github' => 'GitHub',
            'manual' => 'Manual',
            default  => ucfirst($this->source),
        };
    }

    public function riskLevel(): string
    {
        return $this->aiSummary?->risk_level ?? 'low';
    }

    public function riskColor(): string
    {
        return match ($this->riskLevel()) {
            'low'      => 'green',
            'medium'   => 'yellow',
            'high'     => 'orange',
            'critical' => 'red',
            default    => 'gray',
        };
    }

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function aiSummary()
    {
        return $this->hasOne(AiSummary::class);
    }
}
