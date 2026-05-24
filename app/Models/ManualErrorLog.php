<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualErrorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'environment',
        'error_message',
        'stack_trace',
        'severity',
        'notes',
    ];

    public function severityColor(): string
    {
        return match ($this->severity) {
            'low'      => 'green',
            'medium'   => 'yellow',
            'high'     => 'orange',
            'critical' => 'red',
            default    => 'gray',
        };
    }

    public function severityLabel(): string
    {
        return match ($this->severity) {
            'low'      => 'Low',
            'medium'   => 'Medium',
            'high'     => 'High',
            'critical' => 'Critical',
            default    => ucfirst($this->severity),
        };
    }

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
