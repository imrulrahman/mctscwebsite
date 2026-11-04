<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'action',
        'module',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
        'properties',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest('id');
    }

    /**
     * Helper to get an icon or color badge depending on action or module
     */
    public function actionBadgeColor(): string
    {
        return match ($this->action) {
            'login' => '#10b981',
            'logout' => '#6b7280',
            'failed_login' => '#ef4444',
            'delete' => '#ef4444',
            'create' => '#0284c7',
            'update', 'edit' => '#f59e0b',
            'change_banner' => '#8b5cf6',
            default => '#475569',
        };
    }
}
