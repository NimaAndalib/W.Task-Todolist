<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>to do list</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="text-white text-center">
    <div class="transparent"></div>
    <div class="container pt-1">
        <div class="text-center">
            <img src="{{ asset('img/logo.png') }}" alt="لوگو" width="150">
        </div>

        <div class="box mx-auto p-4 rounded-4">
            <h2 class="text-center mb-3">ثبت نام</h2>
            <hr class="my-3">

            <form action="{{ route('register') }}" id="registerForm" class="text-start" method="post">
                @csrf

                <!-- فیلد نام کامل -->
                <div class="mb-3">
                    <label for="fullName" class="form-label">نام و نام خانوادگی</label>
                    <input type="text" class="form-control name-input" id="fullName" name="name" required
                        autocomplete="off">
                </div>

                <!-- فیلد ایمیل -->
                <div class="mb-3">
                    <label for="email" class="form-label">ایمیل</label>
                    <input type="email" class="form-control email-input" id="email" name="email" required
                        autocomplete="off">
                </div>

                <!-- فیلد رمز عبور -->
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">رمز عبور</label>
                    <input type="password" class="form-control pass-input" id="password" name="password" required
                        autocomplete="off">
                    <i class="bi bi-eye-fill position-absolute translate-middle-y me-3" id="togglePassword"
                        style="cursor: pointer; z-index: 2;"></i>
                </div>

                <!-- فیلد تکرار رمز عبور -->
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">تایید رمز عبور</label>
                    <input type="password" class="form-control pass2-input" id="confirmPassword"
                        name="password_confirmation" required>
                    <div class="invalid-feedback" id="passwordError">پسوردها مطابقت ندارند!</div>
                </div>

                <!-- چک باکس قوانین -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                    <label class="form-check-label" for="agreeTerms">
                        <a href="#" class="text-info">قوانین و حریم خصوصی</a> را پذیرفته ام
                    </label>
                </div>

                <!-- دکمه ثبت نام -->
                <button type="submit" class="btn submit-btn w-100 py-2">مرحله بعد</button>

                <!-- لینک ورود -->
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-info">حساب کاربری دارید؟ وارد شوید</a>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/register.js') }}"></script>
</body>

</html>
