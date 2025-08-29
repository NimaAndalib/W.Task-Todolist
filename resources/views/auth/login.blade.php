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
    <div class="transparent"></div>
    <div class="container">
        <h1 class="text-center pt-1 pb-1">
            <img src="{{ asset('img/logo.png') }}" alt="لوگو" class="img-fluid">
        </h1>

        <div class="box mx-auto p-4 rounded-4 mt-2">
            <h2 class="text-center mb-3">ورود</h2>
            <p class="text-center mb-4">وارد حساب کاربری خود شوید</p>
            <hr class="my-4">

            <form id="login-form" action="{{ route('login.attempt') }}" method="POST" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">ایمیل</label>
                    <input type="email" class="form-control" id="email" name="email" required
                        value="{{ old('email') }}" autofocus>
                </div>

                <div class="mb-3 pass-input position-relative">
                    <label for="password" class="form-label">رمز عبور</label>
                    <input type="password" class="form-control" id="password" name="password" required
                        autocomplete="off">
                    <i class="bi bi-eye-fill position-absolute" id="togglePassword"></i>
                </div>

                <div id="login-error" class="text-start text-danger my-2 fw-bold"></div>
                <div class="d-flex justify-content-between mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
                    </div>
                    <a href="" class="text-info">فراموشی رمز عبور؟</a>
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

    <script>
        // AJAX Login
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault(); // صفحه ریفرش نشه

            const form = e.target;
            const formData = new FormData(form);
            const errorDiv = document.getElementById('login-error');

            // پاک کردن خطاهای قبلی
            errorDiv.textContent = '';

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        return response.json(); // موفقیت
                    } else {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                })
                .then(data => {
                    // موفقیت: ریدایرکت به داشبورد
                    window.location.href = data.redirect || '/dashboard';
                })
                .catch(err => {
                    // وقتی credential اشتباهه Fortify 422 می‌ده
                    if (err.errors && err.errors.email) {
                        errorDiv.textContent = "رمز عبور یا ایمیل کاربر اشتباه میباشد";
                    }
                });
        });
    </script>

</body>

</html>
