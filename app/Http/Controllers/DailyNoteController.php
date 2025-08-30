<?php

namespace App\Http\Controllers;

use App\Models\DailyNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// ================================================================
// CONTROLLER: مدیریت عملیات مربوط به یادداشت‌های روزانه
// ================================================================
class DailyNoteController extends Controller
{
    /**
     * GET: دریافت یادداشت روز جاری - در صورت عدم وجود، رکورد جدید ایجاد می‌کند
     */
    public function getNote()
    {
        $today = Carbon::today()->toDateString();
        
        // DB: پیدا کردن یا ایجاد یادداشت برای کاربر و تاریخ جاری
        $note = DailyNote::firstOrCreate(
            ['user_id' => Auth::id(), 'date' => $today],
            ['note' => null]
        );

        return response()->json($note);
    }

    /**
     * POST: به‌روزرسانی یادداشت روز جاری
     */
    public function updateNote(Request $request)
    {
        $today = Carbon::today()->toDateString();
        
        // DB: پیدا کردن یادداشت کاربر برای تاریخ جاری
        $note = DailyNote::where('user_id', Auth::id())
            ->where('date', $today)
            ->firstOrFail();

        // APP: به‌روزرسانی محتوای یادداشت
        $note->update(['note' => $request->note]);

        return response()->json(['success' => true]);
    }

    /**
     * DELETE: حذف محتوای یادداشت روز جاری (تنظیم note به null)
     */
    public function deleteNote()
    {
        $today = Carbon::today()->toDateString();
        
        // DB: پیدا کردن یادداشت کاربر برای تاریخ جاری
        $note = DailyNote::where('user_id', Auth::id())
            ->where('date', $today)
            ->first();

        // APP: در صورت وجود یادداشت، محتوای آن حذف می‌شود
        if ($note) {
            $note->update(['note' => null]);
        }

        return response()->json(['success' => true]);
    }
}