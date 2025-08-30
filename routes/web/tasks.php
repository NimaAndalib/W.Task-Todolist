<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// ================================================================
// TASK: مدیریت تسک‌ها - فقط برای کاربران احراز هویت شده
// ================================================================
Route::middleware(['auth'])->group(function () {

    // ========================
    // VIEW: نمایش لیست تسک‌ها
    // ========================
    Route::get('/task', [TaskController::class, 'index'])->name('task');

    // ========================
    // TASK: ایجاد تسک جدید
    // ========================
    Route::post('/task', [TaskController::class, 'store'])->name('task.store');

    // ========================
    // TASK: به‌روزرسانی تسک
    // ========================
    Route::put('/task/{task}', [TaskController::class, 'update'])->name('task.update');

    // ========================
    // TASK: حذف تسک
    // ========================
    Route::delete('/task/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
});

// ================================================================
// پیشرفت تسک‌ها (بدون میدلور auth)
// ================================================================
Route::get('/tasks/progress', [TaskController::class, 'progress'])->name('task.progress');
