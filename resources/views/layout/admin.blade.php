<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-dark .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #fff;
        }
    </style>
</head>

<body>

    <div id="app">
        <nav class="px-4 fixed-top navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">AdminDashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class=" collapse navbar-collapse" id="navbarContent">
                <!-- Left -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @php $url = url()->current(); @endphp
                    <li class="nav-item"><a class="nav-link {{ str_contains($url, 'user') ? 'active' : '' }}"
                            href="{{ route('user.index') }}">Người dùng</a></li>
                    <li class="nav-item"><a class="nav-link {{ str_contains($url, 'category') ? 'active' : '' }}"
                            href="{{ route('category.index') }}">Danh mục</a></li>
                    <li class="nav-item"><a class="nav-link {{ str_contains($url, 'product') ? 'active' : '' }}"
                            href="{{ route('product.index') }}">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link {{ str_contains($url, 'orders') ? 'active' : '' }}"
                            href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                    <li class="nav-item"><a class="nav-link {{ str_contains($url, 'comments') ? 'active' : '' }}"
                            href="{{ route('admin.comments.index') }}">Bình luận</a></li>
                    <li class="nav-item"><a class="nav-link {{ str_contains($url, 'blogs') ? 'active' : '' }}"
                            href="{{ route('blogs.index') }}">Bài viết</a></li>
                    <li class="nav-item"><a class="nav-link {{ str_contains($url, 'banners') ? 'active' : '' }}"
                            href="{{ route('banners.index') }}">Banner</a></li>
                </ul>
                <form class="d-flex" action="{{ url()->current() }}" method="GET">
                    <input name="q" class="form-control me-2" type="search" placeholder="Tìm kiếm..."
                        aria-label="Search" value="{{ request('q') }}">
                    <button class="btn btn-outline-success" type="submit">Tìm</button>
                </form>


                <!-- Right -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-capitalize" href="#" role="button"
                                data-bs-toggle="dropdown">
                                Admin: {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item fw-bold" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>
        <!-- Main Content -->
        <main class="px-4" style="margin-top: 60px;">
            @yield('content')
        </main>
    </div>

    <!-- Toast Notifications -->
    @if (session('success') || session('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div id="toast-message"
                class="toast align-items-center text-white 
            {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') ?? session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

        <script>
            const toastLive = document.getElementById('toast-message');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLive);
            toastBootstrap.show();
        </script>
    @endif

</body>

</html>
