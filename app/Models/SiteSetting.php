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

    public static function getRequiredAdmissionFields(): array
    {
        $val = static::where('key', 'admission_required_fields')->value('value');
        if ($val !== null) {
            $decoded = json_decode($val, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        // Default minimal required fields before admin customizes in Site Settings
        return [
            'contact_no',
        ];
    }

    public static function isAdmissionFieldRequired(string $field): bool
    {
        if (in_array($field, ['course', 'trainee_name_en'])) {
            return true;
        }
        $req = static::getRequiredAdmissionFields();
        return in_array($field, $req);
    }
}
