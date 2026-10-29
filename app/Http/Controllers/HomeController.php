<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;
use App\Models\SiteSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $published = fn (string $section, int $limit = 20) => SiteContent::query()->published()->where('section', $section)->orderBy('sort_order')->orderByDesc('created_at')->limit($limit)->get();
        $settings = SiteSetting::query()->pluck('value', 'key');

        $activities = SiteContent::query()
            ->published()
            ->where('section', 'activities')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return view('home', [
            'heroSlides' => $published('hero_slides', 50),
            'courses' => $published('courses', 4),
            'projects' => $published('projects', 6),
            'partners' => $published('partners', 50),
            'notices' => $published('notices', 8),
            'events' => $published('events', 3),
            'gallery' => $published('gallery', 12),
            'activities' => $activities,
            'settings' => $settings,
        ]);
    }
}
