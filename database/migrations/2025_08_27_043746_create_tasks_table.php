<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // ربط به یوزر
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // فیلدهای تسک
            $table->string('title'); // عنوان تسک
            $table->text('description')->nullable(); // توضیحات
            $table->date('date'); // تاریخ
            $table->time('start_time'); // ساعت شروع
            $table->tinyInteger('priority')->default(0); // میزان اهمیت (0,1,2,3)
            $table->boolean('completed')->default(false); // وضعیت انجام شدن یا نه
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
