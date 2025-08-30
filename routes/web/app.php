<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// ================================================================
// APP: روت‌های اصلی برنامه - فقط برای کاربران احراز هویت شده
// ================================================================
Route::middleware('auth')->group(function () {
    
    // ========================
    // VIEW: صفحات اصلی
    // ========================
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/task', function () {
        return view('pages.task');
    })->name('task');

    Route::get('/activiti', function () {
        return view('pages.activiti');
    })->name('activiti');

    // ========================
    // AUTH: مدیریت احراز هویت
    // ========================
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});