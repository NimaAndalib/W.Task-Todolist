<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// ================================================================
// PROFILE: مدیریت پروفایل کاربر - فقط برای کاربران احراز هویت شده
// ================================================================
Route::middleware('auth')->group(function () {
    
    // ========================
    // VIEW: دریافت اطلاعات پروفایل کاربر
    // ========================
    Route::get('/profile/data', [ProfileController::class, 'getData'])->name('profile.data');
    
    // ========================
    // PROFILE: به‌روزرسانی اطلاعات پروفایل کاربر
    // ========================
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});