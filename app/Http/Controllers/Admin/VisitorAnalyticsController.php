<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VisitorAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        abort_unless($user && ($user->isSystemAdmin() || $user->is_admin || $user->can('settings.manage') || $user->can('users.view')), 403);

        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        // Date range filter
        $range = $request->input('range', 'today');
        $startDate = match ($range) {
            'yesterday' => $yesterday,
            '7days' => Carbon::today()->subDays(6)->toDateString(),
            '30days' => Carbon::today()->subDays(29)->toDateString(),
            'this_month' => Carbon::today()->startOfMonth()->toDateString(),
            'all' => null,
            default => $today, // 'today'
        };

        $endDate = match ($range) {
            'yesterday' => $yesterday,
            default => $today,
        };

        // Query for filtered period
        $periodQuery = VisitorLog::query();
        if ($startDate) {
            $periodQuery->whereBetween('visit_date', [$startDate, $endDate]);
        }

        // Today metrics
        $todayTotal = VisitorLog::where('visit_date', $today)->count();
        $todayUnique = VisitorLog::where('visit_date', $today)->where('is_unique_today', true)->count();

        // Yesterday metrics
        $yesterdayTotal = VisitorLog::where('visit_date', $yesterday)->count();
        $yesterdayUnique = VisitorLog::where('visit_date', $yesterday)->where('is_unique_today', true)->count();

        // Total all time
        $allTimeTotal = VisitorLog::count();
        $allTimeUnique = VisitorLog::where('is_unique_today', true)->count();

        // Filtered period metrics
        $filteredTotal = (clone $periodQuery)->count();
        $filteredUnique = (clone $periodQuery)->where('is_unique_today', true)->count();

        // Country breakdown for selected period
        $countryStats = (clone $periodQuery)
            ->select('country_code', 'country_name', DB::raw('count(*) as total_visits'), DB::raw('sum(case when is_unique_today = 1 then 1 else 0 end) as unique_visits'))
            ->groupBy('country_code', 'country_name')
            ->orderByDesc('total_visits')
            ->limit(15)
            ->get();

        // Add flag emojis
        $countryStats->transform(function ($item) {
            $log = new VisitorLog(['country_code' => $item->country_code]);
            $item->flag = $log->getCountryFlagEmoji();
            return $item;
        });

        // Device breakdown
        $deviceStats = (clone $periodQuery)
            ->select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        // Browser breakdown
        $browserStats = (clone $periodQuery)
            ->select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Paginated log table with filters
        $logQuery = VisitorLog::query()->latest('id');

        if ($startDate) {
            $logQuery->whereBetween('visit_date', [$startDate, $endDate]);
        }

        if ($request->filled('country')) {
            $logQuery->where('country_code', $request->country);
        }

        if ($request->filled('device')) {
            $logQuery->where('device_type', $request->device);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $logQuery->where(function ($sub) use ($q) {
                $sub->where('ip_address', 'like', "%{$q}%")
                    ->orWhere('page_url', 'like', "%{$q}%")
                    ->orWhere('country_name', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            });
        }

        $logs = $logQuery->paginate(25)->withQueryString();

        return view('admin.visitors.index', compact(
            'todayTotal',
            'todayUnique',
            'yesterdayTotal',
            'yesterdayUnique',
            'allTimeTotal',
            'allTimeUnique',
            'filteredTotal',
            'filteredUnique',
            'countryStats',
            'deviceStats',
            'browserStats',
            'logs',
            'range'
        ));
    }
}
