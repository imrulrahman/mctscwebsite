<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use App\Models\SiteContent;
use App\Models\Student;
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

        // Trainee / Student Demographic Summary
        $traineeStats = [
            'total' => Student::count(),
            'male' => Student::whereRaw("LOWER(gender) = 'male'")->count(),
            'female' => Student::whereRaw("LOWER(gender) = 'female'")->count(),
            'ethnic' => Student::whereRaw("LOWER(ethnic) = 'yes'")->count(),
            'pwd' => Student::whereRaw("LOWER(pwd) = 'yes'")->count(),
        ];

        // Occupation-wise Trainee Breakdown
        $occupationStats = Student::query()
            ->select(
                'occupation',
                DB::raw('MAX(occupation_code) as occupation_code'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN LOWER(gender) = 'male' THEN 1 ELSE 0 END) as male_count"),
                DB::raw("SUM(CASE WHEN LOWER(gender) = 'female' THEN 1 ELSE 0 END) as female_count"),
                DB::raw("SUM(CASE WHEN LOWER(ethnic) = 'yes' THEN 1 ELSE 0 END) as ethnic_count"),
                DB::raw("SUM(CASE WHEN LOWER(pwd) = 'yes' THEN 1 ELSE 0 END) as pwd_count")
            )
            ->whereNotNull('occupation')
            ->where('occupation', '!=', '')
            ->groupBy('occupation')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', [
            'counts' => $counts,
            'recent' => SiteContent::query()->latest('updated_at')->limit(8)->get(),
            'projects' => SiteContent::query()->published()->where('section', 'projects')->orderBy('sort_order')->limit(5)->get(),
            'totalApplicationCount' => AdmissionApplication::count(),
            'pendingApplicationCount' => AdmissionApplication::where('status', 'new')->count(),
            'applicationCount' => AdmissionApplication::count(),
            'messageCount' => ContactMessage::query()->where('is_read', false)->count(),
            'todayVisitorsCount' => $todayVisitorsCount,
            'todayUniqueVisitorsCount' => $todayUniqueVisitorsCount,
            'todayCountries' => $todayCountries,
            'traineeStats' => $traineeStats,
            'occupationStats' => $occupationStats,
        ]);
    }
}
