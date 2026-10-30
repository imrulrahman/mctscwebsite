<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = ['section', 'title', 'slug', 'category', 'admission_fee', 'excerpt', 'body', 'image', 'status', 'sort_order', 'starts_at', 'ends_at', 'metadata'];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'admission_fee' => 'decimal:2',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByRaw('CASE WHEN sort_order > 0 THEN sort_order ELSE 999999 END ASC')
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }
}
