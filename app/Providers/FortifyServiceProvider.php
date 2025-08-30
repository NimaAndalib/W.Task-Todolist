<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

// ================================================================
// SERVICE PROVIDER: پیکربندی و راه‌اندازی Laravel Fortify
// ================================================================
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * SERVICE: ثبت سرویس‌ها و dependencyهای اختصاصی Fortify
     */
    public function register(): void
    {
        // NOTE: امکان ثبت سرویس‌های اختصاصی Fortify در این بخش
    }

    /**
     * BOOT: راه‌اندازی و پیکربندی اصلی Fortify
     */
    public function boot(): void
    {
        // ========================
        // VIEW: پیکربندی ویوهای احراز هویت
        // ========================
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::registerView(fn() => view('auth.register'));
        // Fortify::requestPasswordResetLinkView(fn() => view('auth.password.forgot-password'));
        // Fortify::resetPasswordView(fn($request) => view('auth.password.reset-password', ['request' => $request]));

        // ========================
        // AUTH: پیکربندی اکشن‌های مدیریت کاربران
        // ========================
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // ========================
        // SECURITY: پیکربندی محدودیت‌های نرخ درخواست
        // ========================
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}