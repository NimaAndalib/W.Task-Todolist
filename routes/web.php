<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// 🔐 روت‌های احراز هویت
require __DIR__.'/auth/authentication.php';
// require __DIR__.'/auth/password.php';

// 🚀 روت‌های برنامه
require __DIR__.'/web/app.php';
require __DIR__.'/web/profile.php';
require __DIR__.'/web/tasks.php';

// ❌ خطای 404
Route::fallback(function () {
    abort(404, 'صفحه مورد نظر یافت نشد');
});

// نمایش و ذخیره یادداشت
Route::get('/note', [NoteController::class, 'show'])->name('note.show');
Route::post('/note', [NoteController::class, 'store'])->name('note.store');
Route::put('/note/{id}', [NoteController::class, 'update'])->name('note.update');
Route::delete('/note/{id}', [NoteController::class, 'destroy'])->name('note.destroy');

// پیشرفت تسک‌ها
Route::middleware('auth')->get('/tasks/progress', [TaskController::class, 'progress']);
