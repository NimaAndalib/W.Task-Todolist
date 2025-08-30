<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DailyNoteController;

// ================================================================
// APP: مدیریت یادداشت روزانه - فقط برای کاربران احراز هویت شده
// ================================================================
Route::middleware(['auth'])->group(function () {
    
    // ========================
    // VIEW: دریافت یادداشت
    // ========================
    Route::get('/daily-note', [DailyNoteController::class, 'getNote'])->name('daily-note.get');
    
    // ========================
    // APP: به‌روزرسانی یادداشت
    // ========================
    Route::post('/daily-note', [DailyNoteController::class, 'updateNote'])->name('daily-note.update');
    
    // ========================
    // APP: حذف یادداشت
    // ========================
    Route::delete('/daily-note', [DailyNoteController::class, 'deleteNote'])->name('daily-note.delete');
});