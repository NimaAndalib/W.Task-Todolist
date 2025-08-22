<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>to do list</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="text-white">
    <<div class="transparent">
        </div>
        <div class="container">
            <h1 class="text-center pt-1 pb-1">
                <img src="{{ asset('img/logo.png') }}" alt="لوگو" class="img-fluid">
            </h1>

            <div class="box mx-auto p-4 rounded-4 mt-2">
                <h2 class="text-center mb-3">ورود</h2>
                <p class="text-center mb-4">وارد حساب کاربری خود شوید</p>
                <hr class="my-4">

                <form action="{{ route('login') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <input type="email" class="form-control" id="email" name="email" required autoocmletpe="off"
                            autofocus>
                    </div>

                    <div class="mb-3 pass-input position-relative">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control" id="password" name="password" required
                            autocomplete="off">
                        <i class="bi bi-eye-fill position-absolute" id="togglePassword"></i>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
                        </div>
                        <a href="{{ route('forgot') }}" class="text-info">فراموشی رمز عبور؟</a>
                    </div>

                    <button type="submit" class="btn btn-login w-100 py-2 text-white">ورود</button>

                    <div class="text-center mt-3">
                        <span>حساب کاربری ندارید؟</span>
                        <a href="{{ route('register') }}" class="text-info me-2">ثبت نام</a>
                    </div>
                </form>
            </div>
        </div>


        <script src="{{ asset('js/bootstrap.js') }}"></script>
        <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>