<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminOnly;

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
