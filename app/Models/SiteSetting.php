<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function url(string $key, ?string $defaultAsset = null): string
    {
        $val = static::where('key', $key)->value('value');
        if ($val) {
            return str_starts_with($val, 'assets/') ? asset($val) : Storage::url($val);
        }
        return $defaultAsset ? asset($defaultAsset) : '';
    }
}
