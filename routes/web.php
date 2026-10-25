<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteFormsController;
use App\Http\Controllers\SitePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', [SitePageController::class, 'about'])->name('about');
Route::get('/courses', [SitePageController::class, 'courses'])->name('courses');
Route::get('/projects', [SitePageController::class, 'projects'])->name('projects');
Route::get('/notices', [SitePageController::class, 'notices'])->name('notices');
Route::get('/gallery', [SitePageController::class, 'gallery'])->name('gallery');
Route::get('/contact', [SitePageController::class, 'contact'])->name('contact');
Route::get('/admissions', function () {
    return redirect()->route('apply');
})->name('admissions');

Route::get('/apply', [SiteFormsController::class, 'showApplyForm'])->name('apply');
Route::post('/apply', [SiteFormsController::class, 'apply'])->middleware('throttle:15,1')->name('apply.store');
Route::get('/apply/confirmation/{tracking_no}', [SiteFormsController::class, 'applyConfirmation'])->name('apply.confirmation');
Route::post('/apply/confirm-payment/{tracking_no}', [SiteFormsController::class, 'confirmPayment'])->name('apply.payment.confirm');
Route::post('/contact', [SiteFormsController::class, 'contact'])->middleware('throttle:8,1')->name('contact.store');

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'bn'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// Web-based migration & cache clear route for cPanel without Terminal
Route::get('/deploy-update', function () {
    if (!auth()->check() && request('key') !== 'mnctsc2026') {
        abort(403, 'Unauthorized access. Provide correct key or login as admin.');
    }
    $gitOutput = 'shell_exec not available';
    if (function_exists('shell_exec')) {
        $gitOutput = shell_exec('cd ' . escapeshellarg(base_path()) . ' && git pull origin main 2>&1') ?? 'No git output';
    }
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    $mig = \Illuminate\Support\Facades\Artisan::output();
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    $vOut = \Illuminate\Support\Facades\Artisan::output();
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    $opt = trim($vOut . "\n" . \Illuminate\Support\Facades\Artisan::output());
    
    return response("<div style='max-width:700px;margin:50px auto;background:#1e293b;color:#f8fafc;padding:32px;border-radius:12px;font-family:system-ui,monospace;box-shadow:0 10px 25px rgba(0,0,0,0.3);'>
        <h2 style='color:#34d399;margin-top:0;'>✓ Live Server Updated Successfully!</h2>
        <p style='color:#94a3b8;font-size:14px;'>Git repository, database migrations and application caches were refreshed.</p>
        <hr style='border:none;border-top:1px solid #334155;margin:20px 0;'>
        <h4 style='color:#38bdf8;margin-bottom:6px;'>Git Pull Output:</h4>
        <pre style='background:#0f172a;padding:14px;border-radius:6px;color:#fef08a;font-size:13px;white-space:pre-wrap;'>" . e(trim($gitOutput)) . "</pre>
        <h4 style='color:#38bdf8;margin-bottom:6px;'>Database Migrations:</h4>
        <pre style='background:#0f172a;padding:14px;border-radius:6px;color:#a7f3d0;font-size:13px;white-space:pre-wrap;'>" . e(trim($mig) ?: 'Nothing to migrate. All tables are up to date.') . "</pre>
        <h4 style='color:#38bdf8;margin:18px 0 6px;'>Cache Clear:</h4>
        <pre style='background:#0f172a;padding:14px;border-radius:6px;color:#cbd5e1;font-size:13px;white-space:pre-wrap;'>" . e(trim($opt)) . "</pre>
        <div style='margin-top:25px;display:flex;gap:12px;'>
            <a href='" . url('/') . "' style='background:#10b981;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;font-weight:700;'>Visit Homepage &rarr;</a>
            <a href='" . route('admin.dashboard') . "' style='background:#475569;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;'>Admin Dashboard</a>
        </div>
    </div>");
})->name('deploy.update');

// Subfolder route support for local XAMPP environment (/MNCTSC/...)
Route::prefix('MNCTSC')->group(function () {
    Route::get('/', HomeController::class);
    Route::get('/about', [SitePageController::class, 'about']);
    Route::get('/courses', [SitePageController::class, 'courses']);
    Route::get('/projects', [SitePageController::class, 'projects']);
    Route::get('/notices', [SitePageController::class, 'notices']);
    Route::get('/gallery', [SitePageController::class, 'gallery']);
    Route::get('/contact', [SitePageController::class, 'contact']);
    Route::get('/admissions', function () {
        return redirect('/MNCTSC/apply');
    });
    Route::get('/apply', [SiteFormsController::class, 'showApplyForm']);
    Route::post('/apply', [SiteFormsController::class, 'apply'])->middleware('throttle:15,1');
    Route::get('/apply/confirmation/{tracking_no}', [SiteFormsController::class, 'applyConfirmation']);
    Route::post('/contact', [SiteFormsController::class, 'contact'])->middleware('throttle:8,1');
    Route::get('/lang/{locale}', function (string $locale) {
        if (in_array($locale, ['en', 'bn'])) {
            session(['locale' => $locale]);
        }
        return back();
    });
    Route::get('/deploy-update', function () {
        if (!auth()->check() && request('key') !== 'mnctsc2026') {
            abort(403);
        }
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $mig = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $opt = \Illuminate\Support\Facades\Artisan::output();
        return response("<div style='max-width:700px;margin:50px auto;background:#1e293b;color:#f8fafc;padding:32px;border-radius:12px;font-family:system-ui,monospace;'><h2>✓ Updated!</h2><pre>" . e($mig) . "\n" . e($opt) . "</pre></div>");
    });
});

// Storage route fallback for environments where symlinks are not permitted
Route::get('/storage/{path}', function (string $path) {
    $cleanPath = ltrim(preg_replace('#^storage/#', '', $path), '/');
    $candidates = [
        storage_path('app/public/' . $cleanPath),
        public_path('storage/' . $cleanPath),
        base_path('storage/app/public/' . $cleanPath),
        '/home/mctscedu/public_html/storage/' . $cleanPath,
        '/home/mctscedu/storage/app/public/' . $cleanPath,
        storage_path('app/' . $cleanPath),
    ];
    foreach ($candidates as $filePath) {
        if (file_exists($filePath) && !is_dir($filePath)) {
            $mime = @mime_content_type($filePath) ?: 'application/octet-stream';
            return response()->file($filePath, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
    }
    abort(404);
})->where('path', '.*')->name('storage.file');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
    Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/content/{section}', [ContentController::class, 'index'])->where('section', 'courses|projects|notices|events|gallery|partners')->name('content.index');
        Route::get('/content/{section}/create', [ContentController::class, 'create'])->where('section', 'courses|projects|notices|events|gallery|partners')->name('content.create');
        Route::post('/content/{section}', [ContentController::class, 'store'])->where('section', 'courses|projects|notices|events|gallery|partners')->name('content.store');
        Route::get('/content/{section}/{content}/edit', [ContentController::class, 'edit'])->where('section', 'courses|projects|notices|events|gallery|partners')->name('content.edit');
        Route::put('/content/{section}/{content}', [ContentController::class, 'update'])->where('section', 'courses|projects|notices|events|gallery|partners')->name('content.update');
        Route::delete('/content/{section}/{content}', [ContentController::class, 'destroy'])->where('section', 'courses|projects|notices|events|gallery|partners')->name('content.destroy');
        // Trainee Admissions Management
        Route::get('/applications', [InboxController::class, 'applications'])->name('applications');
        Route::get('/applications/create', [InboxController::class, 'createApplication'])->name('applications.create');
        Route::post('/applications', [InboxController::class, 'storeApplication'])->name('applications.store');
        Route::get('/applications/{application}', [InboxController::class, 'showApplication'])->name('applications.show');
        Route::get('/applications/{application}/file/{type}', [InboxController::class, 'serveFile'])->name('applications.file');
        Route::post('/applications/{application}/approve', [InboxController::class, 'approveApplication'])->name('applications.approve');
        Route::post('/applications/{application}/payment', [InboxController::class, 'recordPayment'])->name('applications.payment');
        Route::post('/applications/{application}/undo-payment', [InboxController::class, 'undoPayment'])->name('applications.undo-payment');
        Route::get('/applications/{application}/print', [InboxController::class, 'printApplication'])->name('applications.print');
        Route::get('/applications/{application}/voucher', [InboxController::class, 'printVoucher'])->name('applications.voucher');
        Route::patch('/applications/{application}', [InboxController::class, 'updateApplication'])->name('applications.update');
        Route::delete('/applications/{application}', [InboxController::class, 'destroyApplication'])->name('applications.destroy');

        // Messages Management
        Route::get('/messages', [InboxController::class, 'messages'])->name('messages');
        Route::delete('/messages/{message}', [InboxController::class, 'destroyMessage'])->name('messages.destroy');

        // Site Settings
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
        Route::put('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
        Route::post('/users/{user}/permissions/reset', [UserController::class, 'resetPermissions'])->name('users.permissions.reset');

        // Role Management (System Admin)
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // User Self-Profile & Password
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
