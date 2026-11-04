<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use App\Models\SiteContent;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $counts = [];
        foreach (['courses', 'projects', 'notices', 'gallery'] as $section) {
            $counts[$section] = SiteContent::query()->published()->where('section', $section)->count();
        }

        $today = today()->toDateString();
        $todayVisitorsCount = VisitorLog::where('visit_date', $today)->count();
        $todayUniqueVisitorsCount = VisitorLog::where('visit_date', $today)->where('is_unique_today', true)->count();
        $allTimeVisitorsCount = VisitorLog::count();

        $todayCountries = VisitorLog::where('visit_date', $today)
            ->select('country_code', 'country_name', DB::raw('count(*) as total'))
            ->groupBy('country_code', 'country_name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $todayCountries->transform(function ($item) {
            $log = new VisitorLog(['country_code' => $item->country_code]);
            $item->flag = $log->getCountryFlagEmoji();
            return $item;
        });

        $recentVisitors = VisitorLog::latest('id')->limit(6)->get();
        $recentActivities = ActivityLog::latest('id')->limit(6)->get();

        return view('admin.dashboard', [
            'counts' => $counts,
            'recent' => SiteContent::query()->latest('updated_at')->limit(8)->get(),
            'projects' => SiteContent::query()->published()->where('section', 'projects')->orderBy('sort_order')->limit(5)->get(),
            'totalApplicationCount' => AdmissionApplication::count(),
            'pendingApplicationCount' => AdmissionApplication::where('status', 'new')->count(),
            'applicationCount' => AdmissionApplication::count(),
            'recentApplications' => AdmissionApplication::latest()->limit(8)->get(),
            'messageCount' => ContactMessage::query()->where('is_read', false)->count(),
            'todayVisitorsCount' => $todayVisitorsCount,
            'todayUniqueVisitorsCount' => $todayUniqueVisitorsCount,
            'allTimeVisitorsCount' => $allTimeVisitorsCount,
            'todayCountries' => $todayCountries,
            'recentVisitors' => $recentVisitors,
            'recentActivities' => $recentActivities,
        ]);
    }
}
