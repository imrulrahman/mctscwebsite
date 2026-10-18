<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminOnly;

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

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['admin' => AdminOnly::class]);
        $middleware->redirectGuestsTo('/admin/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
