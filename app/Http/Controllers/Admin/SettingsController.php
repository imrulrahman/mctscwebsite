<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const FIELDS = ['institution_name', 'tagline', 'address', 'phone', 'email', 'about', 'hero_title', 'hero_subtitle'];

    public function edit(): View
    {
        return view('admin.settings', ['settings' => SiteSetting::pluck('value', 'key')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [];
        foreach (self::FIELDS as $field) $rules[$field] = ['nullable', 'string', 'max:2000'];
        $data = $request->validate($rules);
        foreach ($data as $key => $value) SiteSetting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        return back()->with('success', 'প্রতিষ্ঠানের তথ্য সংরক্ষিত হয়েছে।');
    }
}
