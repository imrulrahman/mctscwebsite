<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            try {
                $settings = SiteSetting::query()->pluck('value', 'key');
                $view->with('settings', $settings);
            } catch (\Throwable $e) {
                $view->with('settings', collect([]));
            }
        });
    }
}
