<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteFormsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::post('/apply', [SiteFormsController::class, 'apply'])->middleware('throttle:8,1')->name('apply.store');
Route::post('/contact', [SiteFormsController::class, 'contact'])->middleware('throttle:8,1')->name('contact.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
    Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/content/{section}', [ContentController::class, 'index'])->where('section', 'courses|projects|notices|events|gallery')->name('content.index');
        Route::get('/content/{section}/create', [ContentController::class, 'create'])->where('section', 'courses|projects|notices|events|gallery')->name('content.create');
        Route::post('/content/{section}', [ContentController::class, 'store'])->where('section', 'courses|projects|notices|events|gallery')->name('content.store');
        Route::get('/content/{section}/{content}/edit', [ContentController::class, 'edit'])->where('section', 'courses|projects|notices|events|gallery')->name('content.edit');
        Route::put('/content/{section}/{content}', [ContentController::class, 'update'])->where('section', 'courses|projects|notices|events|gallery')->name('content.update');
        Route::delete('/content/{section}/{content}', [ContentController::class, 'destroy'])->where('section', 'courses|projects|notices|events|gallery')->name('content.destroy');
        Route::get('/applications', [InboxController::class, 'applications'])->name('applications');
        Route::patch('/applications/{application}', [InboxController::class, 'updateApplication'])->name('applications.update');
        Route::get('/messages', [InboxController::class, 'messages'])->name('messages');
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
