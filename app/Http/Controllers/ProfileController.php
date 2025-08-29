<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * به صورت ایجکسی یک فیلد را آپدیت می‌کند.
     * ورودی از سمت JS می‌تواند یکی از کلیدهای nameField, emailField, passwordField
     * یا به‌طور مستقیم name, email, password باشد.
     */
    public function update(Request $request)
    {
        $user = $request->user(); // معادل auth()->user()

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'ابتدا وارد حساب کاربری شوید.'
            ], 401);
        }

        // نگاشت شناسه‌های input در Blade به نام فیلدهای واقعی در دیتابیس
        $map = [
            'nameField'     => 'name',
            'emailField'    => 'email',
            'passwordField' => 'password',
            // پشتیبانی از کلیدهای مستقیم هم برای انعطاف:
            'name'          => 'name',
            'email'         => 'email',
            'password'      => 'password',
        ];

        // تشخیص اینکه کدام فیلد ارسال شده
        $sentKeys = array_intersect(array_keys($map), array_keys($request->all()));
        if (empty($sentKeys)) {
            return response()->json([
                'success' => false,
                'message' => 'هیچ فیلدی برای بروزرسانی ارسال نشده است.'
            ], 422);
        }

        $key       = array_shift($sentKeys);   // مثلاً nameField
        $attribute = $map[$key];               // مثلاً name
        $value     = $request->input($key);

        // قوانین اعتبارسنجی بر اساس فیلد
        $rules = match ($attribute) {
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['required', 'string', 'min:8'],
            default    => ['required'],
        };

        // اعتبارسنجی همان کلید ارسال‌شده
        $validated = $request->validate([$key => $rules]);

        // ذخیره‌سازی
        if ($attribute === 'password') {
            $user->password = Hash::make($value);
        } else {
            // اگر ایمیل عوض شد و مدل قابلیت تأیید ایمیل دارد، می‌توان تایید را ریست کرد
            if ($attribute === 'email' && method_exists($user, 'hasVerifiedEmail')) {
                if ($user->email !== $value && $user->hasVerifiedEmail()) {
                    $user->email_verified_at = null; // اختیاری: ریست کردن تایید ایمیل
                }
            }
            $user->{$attribute} = $value;
        }

        $user->save();

        return response()->json([
            'success'      => true,
            'field'        => $attribute,
            // برای پسورد مقدار واقعی برگردانده نمی‌شود
            'displayValue' => $attribute === 'password' ? '******' : $user->{$attribute},
            'message'      => 'اطلاعات با موفقیت ذخیره شد.',
        ]);
    }
}