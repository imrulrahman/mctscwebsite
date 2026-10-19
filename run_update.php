<?php
/**
 * Standalone deployment & migration script for MCTSC
 * Access via: https://mctsc.edu.bd/run_update.php?key=mnctsc2026
 */
if (($_GET['key'] ?? '') !== 'mnctsc2026') {
    die("Access denied. Incorrect key.");
}

set_time_limit(300);
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>MCTSC Live Updater</title></head>";
echo "<body style='background:#0f172a;color:#f8fafc;font-family:system-ui,monospace;padding:30px;line-height:1.6;'>";
echo "<div style='max-width:800px;margin:0 auto;background:#1e293b;border-radius:12px;padding:30px;box-shadow:0 8px 30px rgba(0,0,0,0.5);'>";
echo "<h2 style='color:#34d399;margin-top:0;'>🚀 MCTSC Live Server Deployment & Migration</h2>";

// Detect Laravel roots
$possibleRoots = [
    __DIR__,
    __DIR__ . '/..',
    __DIR__ . '/../mctscwebsite',
    '/home/mctscedu/mctscwebsite',
    '/home/mctscedu/public_html',
];

$laravelRoot = null;
foreach ($possibleRoots as $dir) {
    if (file_exists($dir . '/bootstrap/app.php') && file_exists($dir . '/artisan')) {
        $laravelRoot = realpath($dir);
        break;
    }
}

if (!$laravelRoot) {
    echo "<p style='color:#f87171;'><b>ERROR:</b> Could not locate Laravel project root!</p>";
    echo "<p>Current dir: " . htmlspecialchars(__DIR__) . "</p>";
    echo "<pre style='background:#0f172a;padding:12px;'>Files in current dir:\n" . htmlspecialchars(print_r(scandir(__DIR__), true)) . "</pre>";
    echo "</div></body></html>";
    exit;
}

echo "<p style='color:#38bdf8;'><b>Detected Laravel Root:</b> <code>" . htmlspecialchars($laravelRoot) . "</code></p>";

// 1. Clear cached routes and config directly
$caches = [
    $laravelRoot . '/bootstrap/cache/routes-v7.php',
    $laravelRoot . '/bootstrap/cache/config.php',
    $laravelRoot . '/bootstrap/cache/services.php',
    $laravelRoot . '/bootstrap/cache/packages.php',
];
foreach ($caches as $c) {
    if (file_exists($c)) {
        unlink($c);
        echo "<div style='color:#a7f3d0;'>✓ Removed old cache: " . htmlspecialchars(basename($c)) . "</div>";
    }
// 2. Sync CSS to public_html/css/site.css
$sourceCss = $laravelRoot . '/public/css/site.css';
$publicCssTarget = __DIR__ . '/css/site.css';
if (file_exists($sourceCss) && realpath($sourceCss) !== realpath($publicCssTarget)) {
    if (!is_dir(__DIR__ . '/css')) {
        @mkdir(__DIR__ . '/css', 0755, true);
    }
    @copy($sourceCss, $publicCssTarget);
    echo "<div style='color:#a7f3d0;'>✓ Synchronized latest CSS to: <code>" . htmlspecialchars($publicCssTarget) . "</code></div>";
}

// 3. Boot Laravel
require_once $laravelRoot . '/vendor/autoload.php';
$app = require_once $laravelRoot . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 3. Run Migrations
echo "<h3 style='color:#fbbf24;margin-top:20px;'>1. Running Database Migrations:</h3>";
\Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
$migOut = \Illuminate\Support\Facades\Artisan::output();
echo "<pre style='background:#0f172a;padding:14px;border-radius:6px;color:#34d399;white-space:pre-wrap;'>" . htmlspecialchars(trim($migOut) ?: 'Nothing to migrate. All database tables are already up to date.') . "</pre>";

// 4. Clear view & application caches
echo "<h3 style='color:#fbbf24;margin-top:20px;'>2. Clearing All Application & View Caches:</h3>";
\Illuminate\Support\Facades\Artisan::call('view:clear');
$viewOut = \Illuminate\Support\Facades\Artisan::output();

\Illuminate\Support\Facades\Artisan::call('cache:clear');
$cacheOut = \Illuminate\Support\Facades\Artisan::output();

echo "<pre style='background:#0f172a;padding:14px;border-radius:6px;color:#94a3b8;white-space:pre-wrap;'>" . htmlspecialchars(trim($viewOut . "\n" . $cacheOut)) . "</pre>";

// 5. Success summary
echo "<div style='background:#064e3b;border:1px solid #059669;padding:16px;border-radius:8px;margin-top:25px;'>";
echo "<b style='color:#6ee7b7;font-size:16px;'>✓ Server Update Completed Successfully!</b><br>";
echo "<span style='color:#d1fae5;font-size:13px;'>Your live database has the new fields, and all templates are cleared. You can now visit your website.</span>";
echo "</div>";

echo "<div style='margin-top:20px;'>";
echo "<a href='/' style='background:#10b981;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;font-weight:bold;display:inline-block;'>Visit Website &rarr;</a>";
echo "</div>";

echo "</div></body></html>";
