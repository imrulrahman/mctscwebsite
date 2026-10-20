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
