<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ================================================================
// MIGRATION: ایجاد جدول daily_notes برای مدیریت یادداشت‌های روزانه کاربران
// ================================================================
return new class extends Migration
{
    /**
     * MIGRATION: اجرای مایگریشن - ایجاد جدول یادداشت‌های روزانه
     */
    public function up()
    {
        Schema::create('daily_notes', function (Blueprint $table) {
            $table->id();
            
            // ========================
            // DB: ارتباط با جدول users
            // ========================
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // ========================
            // DB: فیلدهای محتوای یادداشت
            // ========================
            $table->text('note')->nullable(); // محتوای یادداشت (اختیاری)
            $table->date('date'); // تاریخ یادداشت (فرمت: YYYY-MM-DD)
            
            $table->timestamps();

            // ========================
            // DB: محدودیت یکتایی - هر کاربر در هر روز فقط یک یادداشت
            // ========================
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * MIGRATION: rollback مایگریشن - حذف جدول
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_notes');
    }
};