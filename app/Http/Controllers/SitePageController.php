<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;
use App\Models\SiteSetting;
use Illuminate\View\View;

class SitePageController extends Controller
{
    private function getSettings()
    {
        return SiteSetting::query()->pluck('value', 'key');
    }

    public function about(): View
    {
        $settings = $this->getSettings();
        $gallery = SiteContent::query()->published()->where('section', 'gallery')->orderBy('sort_order')->limit(6)->get();

        return view('site.about', compact('settings', 'gallery'));
    }

    public function infrastructure(): View
    {
        $settings = $this->getSettings();
        return view('site.infrastructure', compact('settings'));
    }

    public function courses(): View
    {
        $settings = $this->getSettings();
        $courses = SiteContent::query()
            ->published()
            ->where('section', 'courses')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('site.courses', compact('settings', 'courses'));
    }

    public function projects(): View
    {
        $settings = $this->getSettings();
        $projects = SiteContent::query()
            ->published()
            ->where('section', 'projects')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('site.projects', compact('settings', 'projects'));
    }

    public function notices(): View
    {
        $settings = $this->getSettings();
        $notices = SiteContent::query()
            ->published()
            ->where('section', 'notices')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('site.notices', compact('settings', 'notices'));
    }

    public function gallery(): View
    {
        $settings = $this->getSettings();
        $gallery = SiteContent::query()
            ->published()
            ->where('section', 'gallery')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('site.gallery', compact('settings', 'gallery'));
    }

    public function career(): View
    {
        $settings = $this->getSettings();
        $circulars = SiteContent::query()
            ->published()
            ->where('section', 'career')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('site.career', compact('settings', 'circulars'));
    }

    public function contact(): View
    {
        $settings = $this->getSettings();

        return view('site.contact', compact('settings'));
    }
}
