<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use App\Models\SiteContent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $counts = [];
        foreach (['courses', 'projects', 'notices', 'gallery'] as $section) {
            $counts[$section] = SiteContent::query()->published()->where('section', $section)->count();
        }

        return view('admin.dashboard', [
            'counts' => $counts,
            'recent' => SiteContent::query()->latest('updated_at')->limit(8)->get(),
            'projects' => SiteContent::query()->published()->where('section', 'projects')->orderBy('sort_order')->limit(5)->get(),
            'applicationCount' => AdmissionApplication::query()->where('status', 'new')->count(),
            'messageCount' => ContactMessage::query()->where('is_read', false)->count(),
        ]);
    }
}
