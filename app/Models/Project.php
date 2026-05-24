<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_name',
        'description',
        'repository_url',
        'github_repo_name',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // Status label helpers
    public function statusLabel(): string
    {
        return match ($this->status) {
            'on_track'  => 'On Track',
            'at_risk'   => 'At Risk',
            'blocked'   => 'Blocked',
            'completed' => 'Completed',
            default     => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'on_track'  => 'green',
            'at_risk'   => 'yellow',
            'blocked'   => 'red',
            'completed' => 'blue',
            default     => 'gray',
        };
    }

    // Relationships
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function engineeringEvents()
    {
        return $this->hasMany(EngineeringEvent::class);
    }

    public function manualErrorLogs()
    {
        return $this->hasMany(ManualErrorLog::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
