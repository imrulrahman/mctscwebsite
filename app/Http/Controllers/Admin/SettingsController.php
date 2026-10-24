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
        // Theme Colors
        'primary_color', 'primary_hover', 'secondary_color', 'secondary_hover',
        // Hero
        'hero_eyebrow', 'hero_title', 'hero_subtitle', 'hero_btn_text',
        'hero_show_institution_name', 'hero_institution_name',
        'hero_text_color', 'hero_show_overlay', 'hero_fit_mode',
        // Notice Bar
        'notice_bar_bg_color', 'notice_bar_opacity', 'notice_bar_text_color',
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
        abort_unless(auth()->user()?->can('settings.manage'), 403, 'সাইট সেটিংস পরিবর্তনের অনুমতি আপনার অ্যাকাউন্টে নেই।');
        return view('admin.settings', ['settings' => SiteSetting::query()->pluck('value', 'key')]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403, 'সাইট সেটিংস পরিবর্তনের অনুমতি আপনার অ্যাকাউন্টে নেই।');
        $rules = [];
        foreach (self::TEXT_FIELDS as $field) {
            $rules[$field] = ['nullable', 'string', 'max:5000'];
        }
        foreach (self::IMAGE_FIELDS as $field) {
            $rules[$field] = ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp,ico', 'max:5120']; // up to 5MB
        }

        $request->validate($rules);

        // Update text fields
        foreach (self::TEXT_FIELDS as $field) {
            if ($request->has($field)) {
                $val = $request->input($field) ?? '';
                if ($field === 'notice_bar_opacity') {
                    $rawOp = floatval($val);
                    $cleanOp = $rawOp <= 1.0 && $rawOp > 0 ? (int)round($rawOp * 100) : (int)$rawOp;
                    if ($cleanOp <= 0) $cleanOp = 10;
                    $val = (string)$cleanOp;
                }
                SiteSetting::updateOrCreate(['key' => $field], ['value' => $val]);
            }
        }

        // Update Admission Form required fields configuration
        if ($request->has('admission_fields_config_submitted')) {
            $reqFields = $request->input('admission_required_fields', []);
            SiteSetting::updateOrCreate(
                ['key' => 'admission_required_fields'],
                ['value' => json_encode(array_values($reqFields))]
            );
        }

        // Handle preset banner selection if no new custom image file uploaded
        if (!$request->hasFile('hero_banner') && $request->filled('preset_banner')) {
            $preset = $request->input('preset_banner');
            if (str_starts_with($preset, 'assets/banners/')) {
                SiteSetting::updateOrCreate(['key' => 'hero_banner'], ['value' => $preset]);
            }
        }

        // Update image uploads
        foreach (self::IMAGE_FIELDS as $field) {
            if ($request->hasFile($field)) {
                $old = SiteSetting::where('key', $field)->value('value');
                if ($old && !str_starts_with($old, 'assets/')) {
                    Storage::disk('public')->delete($old);
                    $oldPub = public_path('storage/' . $old);
                    if (file_exists($oldPub)) @unlink($oldPub);
                }
                $path = $request->file($field)->store('settings', 'public');
                SiteSetting::updateOrCreate(['key' => $field], ['value' => $path]);

                // Mirror to physical public folder
                $source = storage_path('app/public/' . $path);
                if (file_exists($source)) {
                    $targets = [
                        public_path('storage/' . $path),
                        '/home/mctscedu/public_html/storage/' . $path,
                    ];
                    foreach ($targets as $target) {
                        $dir = dirname($target);
                        if (!is_dir($dir) && is_dir(dirname($dir))) {
                            @mkdir($dir, 0775, true);
                        }
                        if (is_dir($dir) && !is_link(dirname($dir))) {
                            @copy($source, $target);
                        }
                    }
                }
            }
        }

        // Clear cached views and settings cache so changes take effect immediately
        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
        } catch (\Throwable $e) {
            // Ignore if artisan calls fail in restricted hosting
        }

        if ($request->input('action') === 'apply_color') {
            return redirect()->to(route('admin.settings') . '#theme-section')
                ->with('success', 'নতুন কালার থিম সফলভাবে ওয়েবসাইটে অ্যাপ্লাই ও সেভ করা হয়েছে!');
        }

        return back()->with('success', 'প্রতিষ্ঠানের তথ্য এবং ওয়েবসাইট সেটিংস সফলভাবে সংরক্ষিত হয়েছে।');
    }
}
