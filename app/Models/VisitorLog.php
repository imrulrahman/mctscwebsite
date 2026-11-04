<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'country_code',
        'country_name',
        'city',
        'device_type',
        'browser',
        'platform',
        'page_url',
        'referer',
        'user_agent',
        'is_unique_today',
        'visit_date',
    ];

    protected function casts(): array
    {
        return [
            'is_unique_today' => 'boolean',
            'visit_date' => 'date',
        ];
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('visit_date', today());
    }

    public function scopeUniqueToday(Builder $query): Builder
    {
        return $query->whereDate('visit_date', today())->where('is_unique_today', true);
    }

    /**
     * Helper to return country flag emoji from 2-letter ISO code
     */
    public function getCountryFlagEmoji(): string
    {
        $code = strtoupper(trim((string)$this->country_code));
        if ($code === 'LOCAL' || $code === 'LAN' || empty($code)) {
            return '🌐';
        }
        if (strlen($code) !== 2) {
            return '🏳️';
        }

        // Convert ISO 3166-1 alpha-2 to regional indicator symbol pair
        $first = ord($code[0]) - 65 + 0x1F1E6;
        $second = ord($code[1]) - 65 + 0x1F1E6;
        return mb_chr($first, 'UTF-8') . mb_chr($second, 'UTF-8');
    }
}
