<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
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

    public function activities(): View
    {
        $settings = $this->getSettings();
        $activities = SiteContent::query()
            ->published()
            ->where('section', 'activities')
            ->ordered()
            ->paginate(12);

        return view('site.activities', compact('settings', 'activities'));
    }

    public function activityShow(string $slug): View
    {
        $settings = $this->getSettings();
        $activity = SiteContent::query()
            ->published()
            ->where('section', 'activities')
            ->where('slug', $slug)
            ->firstOrFail();

        $recentActivities = SiteContent::query()
            ->published()
            ->where('section', 'activities')
            ->where('id', '!=', $activity->id)
            ->ordered()
            ->limit(5)
            ->get();

        return view('site.activity_detail', compact('settings', 'activity', 'recentActivities'));
    }

    public function successStories(Request $request): View
    {
        $settings = $this->getSettings();
        $filter = $request->query('filter', 'success_story');
        
        $query = SiteContent::query()
            ->published()
            ->where('section', 'stories');

        if ($filter === 'success_story') {
            $query->where(function ($q) {
                $q->where('category', 'Success Story')
                  ->orWhere('category', 'like', '%Success%')
                  ->orWhereNull('category')
                  ->orWhere('category', '');
            });
        } elseif ($filter === 'case_study') {
            $query->where(function ($q) {
                $q->where('category', 'Case Study')
                  ->orWhere('category', 'like', '%Case%');
            });
        }

        $stories = $query->ordered()->paginate(12)->withQueryString();
        $currentType = 'success_story';

        return view('site.stories', compact('settings', 'stories', 'filter', 'currentType'));
    }

    public function caseStudies(Request $request): View
    {
        $settings = $this->getSettings();
        $stories = SiteContent::query()
            ->published()
            ->where('section', 'stories')
            ->where(function ($q) {
                $q->where('category', 'Case Study')
                  ->orWhere('category', 'like', '%Case%');
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $filter = 'case_study';
        $currentType = 'case_study';

        return view('site.stories', compact('settings', 'stories', 'filter', 'currentType'));
    }

    public function storyShow(string $slug): View
    {
        $settings = $this->getSettings();
        $story = SiteContent::query()
            ->published()
            ->where('section', 'stories')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedStories = SiteContent::query()
            ->published()
            ->where('section', 'stories')
            ->where('id', '!=', $story->id)
            ->ordered()
            ->limit(5)
            ->get();

        return view('site.story_detail', compact('settings', 'story', 'relatedStories'));
    }

    public function contact(): View
    {
        $settings = $this->getSettings();

        return view('site.contact', compact('settings'));
    }
}
