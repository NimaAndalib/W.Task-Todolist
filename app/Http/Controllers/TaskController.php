<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ================================================================
// CONTROLLER: مدیریت عملیات مربوط به تسک‌ها
// ================================================================
class TaskController extends Controller
{
    /**
     * GET: نمایش صفحه تسک‌ها با لیست تسک‌های کاربر
     */
    public function index()
    {
        // DB: دریافت تسک‌های کاربر به صورت newest-first
        $tasks = Auth::user()->tasks()->latest()->get();
        
        // VIEW: ارسال داده‌ها به صفحه task.blade.php
        return view('pages.task', ['tasks' => $tasks]);
    }

    /**
     * POST: ایجاد تسک جدید
     */
    public function store(Request $request)
    {
        // VALIDATION: اعتبارسنجی داده‌های ورودی
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required',
            'start_time' => 'required',
            'priority' => 'required|in:0,1,2,3',
        ]);

        // DB: ایجاد تسک جدید برای کاربر جاری
        $task = Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'priority' => (int) $request->priority,
            'completed' => false,
        ]);

        // RESPONSE: ارسال پاسخ موفقیت‌آمیز با داده تسک ایجاد شده
        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    /**
     * PUT: به‌روزرسانی تسک موجود
     */
    public function update(Request $request, Task $task)
    {
        // AUTH: بررسی مالکیت تسک - کاربر فقط می‌تواند تسک‌های خود را ویرایش کند
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        // VALIDATION: اعتبارسنجی داده‌های ورودی (با قوانین conditional)
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|required',
            'start_time' => 'sometimes|required',
            'priority' => 'sometimes|required|in:0,1,2,3',
            'completed' => 'sometimes|boolean',
        ]);

        // DB: به‌روزرسانی تسک
        $task->update($validated);

        // RESPONSE: ارسال پاسخ موفقیت‌آمیز با داده‌های به‌روزرسانی شده
        return response()->json([
            'success' => true,
            'task' => $task->fresh(),
        ]);
    }

    /**
     * DELETE: حذف تسک
     */
    public function destroy(Task $task)
    {
        // AUTH: بررسی مالکیت تسک - کاربر فقط می‌تواند تسک‌های خود را حذف کند
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        // DB: حذف تسک از دیتابیس
        $task->delete();

        // RESPONSE: ارسال پاسخ موفقیت‌آمیز
        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * GET: محاسبه درصد پیشرفت تسک‌ها (تعداد کل و تعداد انجام شده)
     */
    public function progress()
    {
        $user = Auth::user();
        
        // DB: دریافت تمام تسک‌های کاربر
        $tasks = $user->tasks()->get();

        // APP: محاسبات آماری
        $total = $tasks->count();
        $completed = $tasks->where('completed', true)->count();
        $percent = $total > 0 ? round(($completed / $total) * 100) : 0;

        // RESPONSE: ارسال آمار پیشرفت
        return response()->json([
            'total' => $total,
            'completed' => $completed,
            'percent' => $percent
        ]);
    }
}