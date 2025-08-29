<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // نمایش صفحه تسک‌ها
    public function index()
    {
        $tasks = Auth::user()->tasks()->latest()->get();
        // اگه از INITIAL_TASKS در Blade استفاده می‌کنی، این $tasks رو پاس بده
        return view('pages.task', ['tasks' => $tasks]);
    }

    // ذخیره تسک جدید
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required',
            'start_time' => 'required',
            'priority' => 'required|in:0,1,2,3',
        ]);

        $task = Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'priority' => (int) $request->priority,
            'completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'task' => $task,
        ]);
    }

    // بروزرسانی تسک
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|required',
            'start_time' => 'sometimes|required',
            'priority' => 'sometimes|required|in:0,1,2,3',
            'completed' => 'sometimes|boolean',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'task' => $task->fresh(),
        ]);
    }

    // حذف تسک
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    // متد جدید: درصد پیشرفت فعالیت‌ها
    public function progress()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->get();

        $total = $tasks->count();
        $completed = $tasks->where('completed', true)->count();
        $percent = $total > 0 ? round(($completed / $total) * 100) : 0;

        return response()->json([
            'total' => $total,
            'completed' => $completed,
            'percent' => $percent
        ]);
    }
}
