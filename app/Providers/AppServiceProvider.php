<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

if (!function_exists('storage_asset')) {
    function storage_asset(?string $path, ?string $default = null): string {
        if (!$path) {
            return $default ? asset($default) : '';
        }
        if (str_starts_with($path, 'assets/') || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return asset($path);
        }
        $cleanPath = ltrim(preg_replace('#^storage/#', '', $path), '/');
        
        // If default is provided, verify file existence on disk
        if ($default) {
            $candidates = [
                public_path('storage/' . $cleanPath),
                storage_path('app/public/' . $cleanPath),
                '/home/mctscedu/public_html/storage/' . $cleanPath,
            ];
            $exists = false;
            foreach ($candidates as $cand) {
                if (file_exists($cand)) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                return asset($default);
            }
        }
        
        return asset('storage/' . $cleanPath);
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
            } catch (\Throwable $e) {
                $view->with('settings', collect([]));
            }
        });
    }
}
