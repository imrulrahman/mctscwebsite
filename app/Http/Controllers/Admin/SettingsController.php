<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const TEXT_FIELDS = [
        // Institution
        'institution_name', 'tagline', 'phone', 'email', 'address',
        'social_facebook', 'social_youtube', 'social_linkedin',
        // Hero
        'hero_eyebrow', 'hero_title', 'hero_subtitle', 'hero_btn_text',
        // About
        'about_title', 'about', 'about_aside',
        // Facilities
        'facility_1_title', 'facility_2_title', 'facility_3_title', 'facility_4_title',
        // Quotes & Admission
        'quote_title', 'footer_tag',
        'admission_title', 'admission_subtitle'
    ];

    private const IMAGE_FIELDS = [
        'site_logo',
        'hero_banner',
        'about_image',
        'facility_1_image',
        'facility_2_image',
        'facility_3_image',
        'facility_4_image',
    ];

    public function edit(): View
    {
        return view('admin.settings', ['settings' => SiteSetting::query()->pluck('value', 'key')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (self::TEXT_FIELDS as $field) {
            $rules[$field] = ['nullable', 'string', 'max:5000'];
        }
        foreach (self::IMAGE_FIELDS as $field) {
            $rules[$field] = ['nullable', 'image', 'max:5120']; // up to 5MB
        }

        $request->validate($rules);

        // Update text fields
        foreach (self::TEXT_FIELDS as $field) {
            if ($request->has($field)) {
                SiteSetting::updateOrCreate(['key' => $field], ['value' => $request->input($field) ?? '']);
            }
        }

        // Update image uploads
        foreach (self::IMAGE_FIELDS as $field) {
            if ($request->hasFile($field)) {
                $old = SiteSetting::where('key', $field)->value('value');
                if ($old && !str_starts_with($old, 'assets/')) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file($field)->store('settings', 'public');
                SiteSetting::updateOrCreate(['key' => $field], ['value' => $path]);
            }
        }

        return back()->with('success', 'প্রতিষ্ঠানের তথ্য এবং ছবি সফলভাবে সংরক্ষিত হয়েছে।');
    }
}
