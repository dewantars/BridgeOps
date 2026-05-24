<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'sender_id',
        'body',
        'sender_role',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
