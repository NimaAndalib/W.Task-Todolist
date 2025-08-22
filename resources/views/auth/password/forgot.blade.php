<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فراموشی رمز عبور</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="text-center">
    <div class="transparent"></div>

    <div class="container">
        <h1 class="text-center pt-3">
            <img src="{{ asset('img/logo.png') }}" alt="لوگوی سایت" class="img-fluid">
        </h1>

        <div class="box mx-auto p-4 mt-3 rounded-4">
            <span class="fs-5 fw-bold d-block text-center">فراموشی رمز عبور</span>
            <hr class="my-3">

            <form id="forgotPasswordForm" autocomplete="off" class="text-start text-white">
                @csrf

                <!-- مرحله ۱: ایمیل -->
                <div class="email-step">
                    <div class="email-input">
                        <label for="email" class="form-label">ایمیل</label>
                        <input type="email" id="email" class="form-control mt-2 input-custom" name="email" required>
                        <div class="email-error error-message mt-1"></div>
                    </div>

                    <button type="button" id="sendOtpBtn" class="btn w-100 mt-3 submit">
                        ارسال کد تایید
                    </button>
                </div>

                <!-- مرحله ۲: کد تایید -->
                <div class="otp-step" style="display: none;">
                    <div class="code-input mt-3">
                        <label for="code" class="form-label">کد تایید</label>
                        <input type="text" id="code" class="form-control mt-2 input-custom" name="code" maxlength="5"
                            required>
                        <div class="code-error error-message mt-1"></div>
                    </div>

                    <div class="timer-box mt-2">
                        <span class="timer" id="timer">02:00</span>
                        <button type="button" id="resendBtn" class="btn p-0 text-white" disabled>
                            ارسال مجدد کد
                        </button>
                    </div>

                    <button type="button" id="verifyOtpBtn" class="btn w-100 mt-3 submit" disabled>
                        تایید کد
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/forgot.js') }}"></script>
    <script>
        window.loginRoute = "{{ route('reset') }}";
    </script>
</body>

</html>