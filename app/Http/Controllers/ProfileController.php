<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

// ================================================================
// CONTROLLER: مدیریت عملیات پروفایل کاربر
// ================================================================
class ProfileController extends Controller
{
    /**
     * POST: به‌روزرسانی ایجکسی یک فیلد از پروفایل کاربر
     * 
     * INPUT: ورودی می‌تواند یکی از کلیدهای nameField, emailField, passwordField
     * یا کلیدهای مستقیم name, email, password باشد
     */
    public function update(Request $request)
    {
        // AUTH: دریافت کاربر لاگین‌شده
        $user = $request->user();
        
        // ERROR: بررسی احراز هویت کاربر
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'ابتدا وارد حساب کاربری شوید.'
            ], 401);
        }

        // ========================
        // CONFIG: نگاشت کلیدهای ورودی به فیلدهای دیتابیس
        // ========================
        $map = [
            'nameField'     => 'name',
            'emailField'    => 'email', 
            'passwordField' => 'password',
            'name'          => 'name',
            'email'         => 'email',
            'password'      => 'password',
        ];

        // APP: تشخیص فیلد ارسال شده توسط کاربر
        $sentKeys = array_intersect(array_keys($map), array_keys($request->all()));
        
        // ERROR: بررسی ارسال فیلد الزامی
        if (empty($sentKeys)) {
            return response()->json([
                'success' => false,
                'message' => 'هیچ فیلدی برای بروزرسانی ارسال نشده است.'
            ], 422);
        }

        $key       = array_shift($sentKeys);
        $attribute = $map[$key];
        $value     = $request->input($key);

        // ========================
        // VALIDATION: قوانین اعتبارسنجی برای هر فیلد
        // ========================
        $rules = match ($attribute) {
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['required', 'string', 'min:8'],
            default    => ['required'],
        };

        // VALIDATION: اعتبارسنجی داده‌های ورودی
        $validated = $request->validate([$key => $rules]);

        // ========================
        // APP: پردازش و ذخیره‌سازی داده‌ها
        // ========================
        if ($attribute === 'password') {
            // SECURITY: هش کردن پسورد قبل از ذخیره‌سازی
            $user->password = Hash::make($value);
        } else {
            // APP: بررسی تغییر ایمیل و ریست کردن تأییدیه در صورت نیاز
            if ($attribute === 'email' && method_exists($user, 'hasVerifiedEmail')) {
                if ($user->email !== $value && $user->hasVerifiedEmail()) {
                    $user->email_verified_at = null;
                }
            }
            $user->{$attribute} = $value;
        }

        // DB: ذخیره‌سازی تغییرات در دیتابیس
        $user->save();

        // RESPONSE: ارسال پاسخ موفقیت‌آمیز
        return response()->json([
            'success'      => true,
            'field'        => $attribute,
            'displayValue' => $attribute === 'password' ? '******' : $user->{$attribute},
            'message'      => 'اطلاعات با موفقیت ذخیره شد.',
        ]);
    }
}