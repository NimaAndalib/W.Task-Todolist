<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// 👤 روت‌های پروفایل کاربر - فقط برای کاربران لاگین‌شده
Route::middleware('auth')->group(function () {
    // 📋 گرفتن اطلاعات کاربر
    Route::get('/profile/data', [ProfileController::class, 'getData'])->name('profile.data');
    // ✏️ آپدیت اطلاعات کاربر
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});