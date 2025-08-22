<nav class="navbar navbar-expand navbar-dark bg-transparent flex-column h-100">
    <div class="container-fluid flex-column h-100 p-0">
       <div class="navbar-main">
         <!-- لوگو -->
        <a href="{{ route('dashboard') }}" class="navbar-brand my-3 mx-auto">
            <img src="{{ asset('img/logo.png') }}" alt="logo" class="img-fluid" style="max-width: 160px;">
        </a>

        <!-- منوی اصلی -->
        <ul class="navbar-nav text-center flex-column w-100 px-2">
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link text-white d-flex align-items-center py-3">
                    <i class="ri-home-6-line me-2"></i>
                    <span class="nav-text">داشبورد</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('task') ? 'active' : '' }}">
                <a href="{{ route('task') }}" class="nav-link text-white d-flex align-items-center py-3">
                    <i class="ri-file-copy-line me-2"></i>
                    <span class="nav-text">تسک‌ها</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('activiti') ? 'active' : '' }}">
                <a href="{{ route('activiti') }}" class="nav-link text-white d-flex align-items-center py-3">
                    <i class="ri-line-chart-line me-2"></i>
                    <span class="nav-text">فعالیت</span>
                </a>
            </li>
        </ul>

       </div>
        <!-- دکمه خروج -->
        <div class="logout-container mx-auto bottom-0 start-0 w-50 bg-dark-gradient py-3">
            <a href="#"
                class="nav-link log-out-btn text-white d-flex align-items-center justify-content-center py-2 px-4">
                <i class="ri-expand-left-fill me-2 transition-all"></i>
                <span class="nav-text fw-bold">خروج  </span>
                <div class="hover-effect"></div>
            </a>
        </div>
    </div>
</nav>

