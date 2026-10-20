<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

if (!function_exists('storage_asset')) {
    function storage_asset(?string $path, ?string $default = null): string {
        if (!$path) {
            return $default ? (function_exists('asset') ? asset($default) : $default) : '';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'assets/')) {
            return function_exists('asset') ? asset($path) : '/' . ltrim($path, '/');
        }
        $cleanPath = ltrim(preg_replace('#^storage/#', '', $path), '/');
        
        // If default is provided, verify file existence on disk
        if ($default) {
            $candidates = [
                storage_path('app/public/' . $cleanPath),
                public_path('storage/' . $cleanPath),
                base_path('storage/app/public/' . $cleanPath),
                '/home/mctscedu/public_html/storage/' . $cleanPath,
                '/home/mctscedu/storage/app/public/' . $cleanPath,
            ];
            $exists = false;
            foreach ($candidates as $cand) {
                if (file_exists($cand) && !is_dir($cand)) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                return function_exists('asset') ? asset($default) : '/' . ltrim($default, '/');
            }
        }
        
        // Always generate URL using current request host & scheme to prevent APP_URL localhost:8080 mismatch
        if (function_exists('request') && request() && request()->getHost()) {
            return rtrim(request()->getSchemeAndHttpHost() . request()->getBasePath(), '/') . '/storage/' . $cleanPath;
        }

        return function_exists('asset') ? asset('storage/' . $cleanPath) : '/storage/' . $cleanPath;
    }
}

if (!function_exists('amount_in_words')) {
    function amount_in_words(float $number): string {
        if ($number <= 0) {
            return 'Zero (Free Govt Scholarship)';
        }
        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen',
            20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
            60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
        ];
        
        $num = (int)round($number);
        $result = '';

        if ($num >= 10000000) {
            $crore = (int)($num / 10000000);
            $result .= amount_in_words($crore) . ' Crore ';
            $num %= 10000000;
        }
        if ($num >= 100000) {
            $lakh = (int)($num / 100000);
            $result .= amount_in_words($lakh) . ' Lakh ';
            $num %= 100000;
        }
        if ($num >= 1000) {
            $thousand = (int)($num / 1000);
            $result .= amount_in_words($thousand) . ' Thousand ';
            $num %= 1000;
        }
        if ($num >= 100) {
            $hundred = (int)($num / 100);
            $result .= amount_in_words($hundred) . ' Hundred ';
            $num %= 100;
        }
        if ($num > 0) {
            if ($num < 20) {
                $result .= $words[$num] . ' ';
            } else {
                $tens = (int)($num / 10) * 10;
                $units = $num % 10;
                $result .= $words[$tens] . ' ';
                if ($units > 0) {
                    $result .= $words[$units] . ' ';
                }
            }
        }
        return trim($result);
    }
}

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, string $ability) {
            if (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin()) {
                return true;
            }

            if (method_exists($user, 'hasPermission')) {
                return $user->hasPermission($ability);
            }

            return null;
        });

        View::composer('*', function ($view) {
            if (request()->has('lang') && in_array(request('lang'), ['en', 'bn'])) {
                session(['locale' => request('lang')]);
            }
            $locale = session('locale', 'en');
            app()->setLocale($locale);
            $view->with('currentLocale', $locale);

            try {
                $settings = SiteSetting::query()->pluck('value', 'key');
                $view->with('settings', $settings);
                $pendingAdmissionsCount = AdmissionApplication::where('status', 'new')->count();
                $view->with('pendingAdmissionsCount', $pendingAdmissionsCount);
                $requiredAdmissionFields = SiteSetting::getRequiredAdmissionFields();
                $view->with('requiredAdmissionFields', $requiredAdmissionFields);
            } catch (\Throwable $e) {
                $view->with('settings', collect([]));
                $view->with('pendingAdmissionsCount', 0);
                $view->with('requiredAdmissionFields', []);
            }
        });
    }
}
