<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// ================================================================
// SERVICE PROVIDER: ارائه‌دهنده سرویس‌های اصلی برنامه
// ================================================================
class AppServiceProvider extends ServiceProvider
{
    /**
     * SERVICE: ثبت سرویس‌ها و وابستگی‌های عمومی برنامه
     * 
     * NOTE: این بخش برای ثبت bindings های container استفاده می‌شود
     */
    public function register(): void
    {
        // NOTE: امکان ثبت سرویس‌ها و وابستگی‌های سفارشی در این بخش
    }

    /**
     * BOOT: راه‌اندازی و پیکربندی سرویس‌های عمومی برنامه
     * 
     * NOTE: این بخش پس از ثبت تمام service providers اجرا می‌شود
     */
    public function boot(): void
    {
        // NOTE: امکان افزودن middleware های، shared data برای views و سایر پیکربندی‌ها
    }
}