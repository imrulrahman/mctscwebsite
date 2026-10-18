<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return function_exists('storage_asset') ? storage_asset($val, $defaultAsset) : asset($defaultAsset ?? '');
    }
}
