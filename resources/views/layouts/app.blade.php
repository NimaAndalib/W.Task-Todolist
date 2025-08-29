<!DOCTYPE html>
<html lang="fa-ir">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    @stack('page-css')
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> 
</head>

<body dir="rtl" class="text-white">
    <div class="transparent"></div>
    <div class="container-fluid">
        <div class="row g-0 box mx-auto">
            <!-- نوار کناری -->
            <div id="navbar" class="col-3 navbar-expand-md navbar-dark flex-column  col-lg-2 head-box">
                <div class="navbox bg-dark h-100">
                    <x-navbar />
                </div>
            </div>

            <!-- محتوای اصلی -->
            <div class="col-md-9 col-lg-10 col-12 main-box p-4 rounded-end-4">
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