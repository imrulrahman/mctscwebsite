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
        'hero_slider_mode', 'hero_slider_fixed_id', 'hero_slider_interval',
        // Notice Bar
        'notice_bar_bg_color', 'notice_bar_opacity', 'notice_bar_text_color',
        // About
        'about_title', 'about', 'about_bn', 'about_aside',
        'about_intro_kicker', 'about_intro_title', 'about_intro_subtitle',
        'about_intro_body', 'about_intro_body_bn',
        'about_commitment',
        // Industry Partnerships
        'industry_partners_kicker', 'industry_partners_title',
        'industry_partners_intro_1', 'industry_partners_intro_2',
        'industry_partners_list_title', 'industry_partners_list',
        'industry_partners_commitment',
        // Facilities
        'infr_labs_kicker', 'infr_labs_title', 'infr_labs_subtitle',
        'facility_1_title', 'facility_2_title', 'facility_3_title', 'facility_4_title',
        'facility_1_desc', 'facility_2_desc', 'facility_3_desc', 'facility_4_desc',
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

        // Excluded request parameters that are not site_settings keys
        $excluded = [
            '_token', '_method', 'action', 'preset_banner',
            'admission_fields_config_submitted', 'admission_required_fields'
        ];

        // Process all submitted text inputs dynamically so every element can be edited
        foreach ($request->except($excluded) as $field => $val) {
            if ($request->hasFile($field)) {
                continue;
            }

            if (is_array($val)) {
                $cleanVal = json_encode($val);
            } else {
                $cleanVal = (string)($val ?? '');
            }

            if ($field === 'notice_bar_opacity') {
                $rawOp = floatval($cleanVal);
                $cleanOp = $rawOp <= 1.0 && $rawOp > 0 ? (int)round($rawOp * 100) : (int)$rawOp;
                if ($cleanOp <= 0) $cleanOp = 10;
                $cleanVal = (string)$cleanOp;
            }

            SiteSetting::updateOrCreate(['key' => $field], ['value' => $cleanVal]);
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
