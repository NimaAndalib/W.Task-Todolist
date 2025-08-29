<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// 🚀 روت‌های اصلی برنامه - فقط برای کاربران لاگین‌شده
Route::middleware('auth')->group(function () {
    // 🏠 صفحات اصلی
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/task', function () {
        return view('pages.task');
    })->name('task');

    Route::get('/activiti', function () {
        return view('pages.activiti');
    })->name('activiti');

    // 🚪 خروج
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});