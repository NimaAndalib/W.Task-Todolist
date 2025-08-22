<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فراموشی رمز عبور</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="transparent"></div>

    <div class="container">
        <h1 class="text-center pt-3">
            <img src="{{ asset('img/logo.png') }}" alt="لوگوی سایت" class="img-fluid" style="width: 150px;">
        </h1>

        <div class="box mx-auto p-4 mt-3 rounded-4">
            <span class="fs-5 fw-bold d-block text-center">رمز جدید</span>
            <hr class="my-3">

            <form id="resetPasswordForm" autocomplete="off">
                @csrf

                <!-- فیلد رمز عبور -->
                <div class="pass_input mb-3 position-relative">
                    <label for="passInput" class="form-label">رمز عبور</label>
                    <div class="input-group">
                        <input type="password" id="passInput" class="form-control input-custom mt-2 rounded-3" name="password"
                            required>
                        <span class="input-group-text bg-transparent border-0 position-absolute"
                            style="z-index: 3; left: 0px; top: 50%; transform: translateY(-60%);">
                            <i class="bi bi-eye-fill" id="togglePassword" style="cursor: pointer;"></i>
                        </span>
                    </div>
                    <span class="error-message text-danger mt-2 d-none" id="errorMessage1">
                        حداقل 8 رقم
                    </span>
                </div>

                <!-- فیلد تکرار رمز عبور -->
                <div class="pass2_input mb-3">
                    <label for="pass2Input" class="form-label">تایید رمز عبور</label>
                    <input type="password" id="pass2Input" class="form-control input-custom mt-2"
                        name="password_confirmation" required>
                    <span class="error-message text-danger mt-2 d-none" id="errorMessage2">
                        پسوردها مطابقت ندارند!
                    </span>
                </div>

                <!-- دکمه ارسال -->
                <div class="btn-box">
                    <button type="submit" id="submit" class="btn submit w-100 mt-3">
                        تایید رمز
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/reset.js') }}"></script>
    <script>
        window.loginRoute = "{{ route('login') }}";
    </script>
</body>

</html>