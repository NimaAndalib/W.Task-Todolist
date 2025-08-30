<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// ================================================================
// AUTH: روت‌های احراز هویت و مدیریت کاربران
// ================================================================
require __DIR__.'/auth/authentication.php';
// require __DIR__.'/auth/password.php';

// ================================================================
// APP: روت‌های اصلی برنامه
// ================================================================
require __DIR__.'/web/app.php';
require __DIR__.'/web/profile.php';
require __DIR__.'/web/tasks.php';
require __DIR__.'/web/dailynote.php';

// ================================================================
// ERROR: مدیریت خطاهای درخواست
// ================================================================
Route::fallback(function () {
    abort(404, 'صفحه مورد نظر یافت نشد');
});


