<?php

namespace App\Services;

use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class VisitorTracker
{
    /**
     * Track a public web visitor.
     */
    public static function track(Request $request): ?VisitorLog
    {
        try {
            // Exclude admin, live-edit, static files, api, health check
            $path = trim($request->path(), '/');
            if (
                str_starts_with($path, 'admin') ||
                str_starts_with($path, 'live-edit') ||
                str_starts_with($path, 'up') ||
                str_starts_with($path, 'api') ||
                str_starts_with($path, 'storage') ||
                str_starts_with($path, 'assets') ||
                str_ends_with($path, '.png') ||
                str_ends_with($path, '.jpg') ||
                str_ends_with($path, '.jpeg') ||
                str_ends_with($path, '.css') ||
                str_ends_with($path, '.js') ||
                str_ends_with($path, '.ico') ||
                str_ends_with($path, '.txt')
            ) {
                return null;
            }

            // Exclude logged in admins if visiting public pages (optional, or flag)
            if (auth()->check() && (auth()->user()?->is_admin || auth()->user()?->role_id)) {
                // We can still log or skip; usually admins testing site might want to see real users or test themselves.
                // We'll let it record with local detection.
            }

            $ip = self::getVisitorIp($request);
            $userAgent = $request->userAgent() ?? '';

            // Detect bot
            if (self::isBot($userAgent)) {
                return null;
            }

            $geo = self::resolveGeoLocation($ip, $request);
            $parsed = self::parseUserAgent($userAgent);

            $today = now()->format('Y-m-d');
            $cacheKey = "visitor_logged_{$ip}_{$today}";
            $isUnique = false;

            if (!Cache::has($cacheKey)) {
                $isUnique = true;
                Cache::put($cacheKey, 1, now()->endOfDay());
            }

            return VisitorLog::create([
                'ip_address' => $ip,
                'country_code' => $geo['country_code'] ?? 'BD',
                'country_name' => $geo['country_name'] ?? 'Bangladesh',
                'city' => $geo['city'] ?? null,
                'device_type' => $parsed['device_type'] ?? 'Desktop',
                'browser' => $parsed['browser'] ?? 'Browser',
                'platform' => $parsed['platform'] ?? 'OS',
                'page_url' => $request->fullUrl(),
                'referer' => $request->header('referer'),
                'user_agent' => substr($userAgent, 0, 500),
                'is_unique_today' => $isUnique,
                'visit_date' => $today,
            ]);
        } catch (\Throwable $e) {
            // Fail silently so visitor tracking never disrupts page rendering
            report($e);
            return null;
        }
    }

    public static function getVisitorIp(Request $request): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
        ];

        foreach ($headers as $header) {
            if ($val = $request->server($header)) {
                $ips = explode(',', $val);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $request->ip() ?: '127.0.0.1';
    }

    public static function resolveGeoLocation(string $ip, Request $request): array
    {
        // 1. Check Cloudflare country header
        $cfCountry = $request->server('HTTP_CF_IPCOUNTRY');
        if (!empty($cfCountry) && strlen($cfCountry) === 2 && strtoupper($cfCountry) !== 'XX') {
            $code = strtoupper($cfCountry);
            return [
                'country_code' => $code,
                'country_name' => self::countryNameFromCode($code),
                'city' => null,
            ];
        }

        // 2. Private or localhost IPs
        if (
            $ip === '127.0.0.1' ||
            $ip === '::1' ||
            str_starts_with($ip, '192.168.') ||
            str_starts_with($ip, '10.') ||
            str_starts_with($ip, '172.16.')
        ) {
            return [
                'country_code' => 'BD',
                'country_name' => 'Bangladesh (Local / Host)',
                'city' => 'Local Network',
            ];
        }

        // 3. Cache public IP resolution for 30 days
        return Cache::remember("geoip_cache_{$ip}", 86400 * 30, function () use ($ip) {
            try {
                $response = Http::timeout(1.5)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,city");
                if ($response->successful() && $response->json('status') === 'success') {
                    return [
                        'country_code' => strtoupper((string)$response->json('countryCode', 'BD')),
                        'country_name' => (string)$response->json('country', 'Bangladesh'),
                        'city' => (string)$response->json('city', ''),
                    ];
                }
            } catch (\Throwable $e) {
                // API timeout or down
            }

            return [
                'country_code' => 'BD',
                'country_name' => 'Bangladesh',
                'city' => null,
            ];
        });
    }

    public static function parseUserAgent(?string $ua): array
    {
        $ua = (string)$ua;

        // Device
        $device = 'Desktop';
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            $device = 'Tablet';
        } elseif (preg_match('/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|NetFront|Silk-Accelerated|(hpw|web)OS|Fennec|Minimo|Opera M(obi|ini)|Blazer/i', $ua)) {
            $device = 'Mobile';
        }

        // Browser
        $browser = 'Other Browser';
        if (preg_match('/Edg/i', $ua)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/Chrome/i', $ua) && !preg_match('/Edg/i', $ua)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $ua) && !preg_match('/Chrome/i', $ua)) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/Firefox/i', $ua)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/Opera|OPR/i', $ua)) {
            $browser = 'Opera';
        }

        // Platform
        $platform = 'Other OS';
        if (preg_match('/windows nt/i', $ua)) {
            $platform = 'Windows';
        } elseif (preg_match('/android/i', $ua)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $ua)) {
            $platform = 'iOS';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $platform = 'macOS';
        } elseif (preg_match('/linux/i', $ua)) {
            $platform = 'Linux';
        }

        return [
            'device_type' => $device,
            'browser' => $browser,
            'platform' => $platform,
        ];
    }

    public static function isBot(?string $ua): bool
    {
        if (empty($ua)) return false;
        return (bool)preg_match('/(bot|crawl|spider|slurp|curl|wget|facebookexternalhit|whatsapp|googlebot|bingbot|yahoo)/i', $ua);
    }

    public static function countryNameFromCode(string $code): string
    {
        $names = [
            'BD' => 'Bangladesh',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'IN' => 'India',
            'SA' => 'Saudi Arabia',
            'AE' => 'United Arab Emirates',
            'MY' => 'Malaysia',
            'SG' => 'Singapore',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'IT' => 'Italy',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'PK' => 'Pakistan',
            'OM' => 'Oman',
            'QA' => 'Qatar',
            'KW' => 'Kuwait',
            'BH' => 'Bahrain',
        ];

        return $names[strtoupper($code)] ?? $code;
    }
}
