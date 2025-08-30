<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ================================================================
// MIGRATION: ایجاد جدول tasks برای مدیریت تسک‌های کاربران
// ================================================================
return new class extends Migration
{
    /**
     * MIGRATION: اجرای مایگریشن - ایجاد جدول
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // ========================
            // DB: ارتباط با جدول users
            // ========================
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // ========================
            // DB: فیلدهای اصلی تسک
            // ========================
            $table->string('title'); // عنوان تسک
            $table->text('description')->nullable(); // توضیحات اختیاری تسک
            $table->date('date'); // تاریخ اجرای تسک
            $table->time('start_time'); // زمان شروع تسک
            
            // ========================
            // DB: فیلدهای وضعیت و اولویت
            // ========================
            $table->tinyInteger('priority')->default(0); // سطح اولویت (0: عادی, 1: کم, 2: متوسط, 3: بالا)
            $table->boolean('completed')->default(false); // وضعیت انجام (true: انجام شده, false: در حال انجام)

            $table->timestamps();
        });
    }

    /**
     * MIGRATION: rollback مایگریشن - حذف جدول
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};