<!DOCTYPE html>
<html lang="fa-ir">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('page-css')
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> 
</head>

<body dir="rtl" class="text-white">
    <div class="transparent"></div>
    <div class="container-fluid">
        <div class="row g-0 box mx-auto">
            <!-- نوار کناری -->
            <div class="col-3 col-lg-2 head-box">
                <x-navbar />
            </div>

            <!-- محتوای اصلی -->
            <div class="col-lg-10 col-9 main-box p-4 rounded-end-4">
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    @stack('script')
</body>

</html>