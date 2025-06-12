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
        <!-- Sidebar -->
        <nav class="d-flex flex-column bg-dark text-white p-3 position-fixed vh-100 shadow" style="width: 250px;">
            <a href="{{ route('admin.dashboard') }}"
                class="text-white fw-bold fs-5 mb-4 text-decoration-none">AdminDashboard</a>

            @php $url = url()->current(); @endphp

            <form class="mb-3" action="{{ url()->current() }}" method="GET">
                <div class="input-group">
                    <input name="q" class="form-control form-control-sm" type="search" placeholder="Tìm kiếm..."
                        value="{{ request('q') }}">
                    <button class="btn btn-sm btn-outline-success" type="submit">Tìm</button>
                </div>
            </form>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link text-white {{ str_contains($url, 'user') ? 'active bg-secondary' : '' }}"
                        href="{{ route('user.index') }}">
                        Quản lý người dùng
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'category') ? 'active bg-secondary' : '' }}"
                        href="{{ route('category.index') }}">
                        Quản lý danh mục
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'product') ? 'active bg-secondary' : '' }}"
                        href="{{ route('product.index') }}">
                        Quản lý Sản phẩm
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'orders') ? 'active bg-secondary' : '' }}"
                        href="{{ route('admin.orders.index') }}">
                        Quản lý đơn hàng
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'comments') ? 'active bg-secondary' : '' }}"
                        href="{{ route('admin.comments.index') }}">
                        Quản lý bình luận
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'blogs') ? 'active bg-secondary' : '' }}"
                        href="{{ route('blogs.index') }}">
                        Quản lý bài viết
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'banners') ? 'active bg-secondary' : '' }}"
                        href="{{ route('banners.index') }}">
                        Quản lý Banner
                    </a>
                </li>
            </ul>

            <hr class="text-white">

            @guest
                <a href="{{ route('login') }}" class="nav-link text-white">Đăng nhập</a>
            @else
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-capitalize text-white" href="#" role="button"
                        data-bs-toggle="dropdown">
                        Admin: {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item fw-bold" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </nav>

        <!-- Main Content -->
        <main class="px-4" style="margin-left: 250px;">
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
