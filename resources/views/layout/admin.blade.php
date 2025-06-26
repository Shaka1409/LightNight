<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        #app.sidebar-hover {
            display: flex;
        }

        /* Sidebar mặc định thu gọn */
        .sidebar {
            width: 80px;
            transition: width 0.3s ease;
            overflow-x: hidden;
            background-color: #212529;
        }

        main {
            margin-left: 80px;
            transition: margin-left 0.3s ease;
            width: 100%;
        }

        /* Khi hover vào sidebar */
        .sidebar-hover .sidebar:hover {
            width: 250px;
        }

        .sidebar-hover .sidebar:hover~main {
            margin-left: 250px;
        }

        /* Label trượt ngang */
        .sidebar-label {
            display: inline-block;
            opacity: 0;
            transform: translateX(-20px);
            transition: transform 0.3s ease, opacity 0.3s ease;
            white-space: nowrap;
        }

        .sidebar-hover .sidebar:hover .sidebar-label {
            opacity: 1;
            transform: translateX(0);
        }

        /* Nav item đẹp hơn */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 6px;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        .nav-link.active {
            background-color: #495057 !important;
        }
    </style>
</head>

<body>
    <div id="app" class="sidebar-hover">

        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column text-white p-3 position-fixed vh-100 shadow">
            <div class="mb-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="text-white fw-bold fs-5 text-decoration-none d-flex align-items-center">
                    <i class="fa-solid fa-bars-staggered me-2"></i>
                    <span class="sidebar-label">AdminDashboard</span>
                </a>
            </div>

            @php $url = url()->current(); @endphp

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link text-white {{ str_contains($url, 'user') ? 'active' : '' }}"
                       href="{{ route('user.index') }}">
                        <i class="fa-solid fa-users"></i>
                        <span class="sidebar-label">Quản lý người dùng</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'category') ? 'active' : '' }}"
                       href="{{ route('category.index') }}">
                        <i class="fa-solid fa-tags"></i>
                        <span class="sidebar-label">Quản lý danh mục</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'product') ? 'active' : '' }}"
                       href="{{ route('product.index') }}">
                        <i class="fa-solid fa-box-open"></i>
                        <span class="sidebar-label">Quản lý sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'orders') ? 'active' : '' }}"
                       href="{{ route('admin.orders.index') }}">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="sidebar-label">Quản lý đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'comments') ? 'active' : '' }}"
                       href="{{ route('admin.comments.index') }}">
                        <i class="fa-solid fa-comments"></i>
                        <span class="sidebar-label">Quản lý bình luận</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'news') ? 'active' : '' }}"
                       href="{{ route('news.index') }}">
                        <i class="fa-solid fa-newspaper"></i>
                        <span class="sidebar-label">Quản lý tin tức</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white {{ str_contains($url, 'banners') ? 'active' : '' }}"
                       href="{{ route('banners.index') }}">
                        <i class="fa-solid fa-image"></i>
                        <span class="sidebar-label">Quản lý Banner</span>
                    </a>
                </li>
            </ul>

            <hr class="text-white">

            @guest
                <a href="{{ route('login') }}" class="nav-link text-white">
                    <i class="fa-solid fa-sign-in-alt"></i>
                    <span class="sidebar-label">Đăng nhập</span>
                </a>
            @else
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#"
                       role="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user-circle"></i>
                        <span class="sidebar-label">Admin: {{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
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
                </div>
            @endguest
        </nav>

        <!-- Main Content -->
        <main class="flex-grow-1 px-4 py-3">
            @yield('content')
        </main>
    </div>

    <!-- Toast Notifications -->
    @if (session('success') || session('error') || session('info'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div id="toast-message"
                 class="toast align-items-center text-white 
                 {{ session('success') ? 'bg-success' : (session('error') ? 'bg-danger' : 'bg-info') }}
                 shadow-lg
                  border-0"
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') ?? session('error') ?? session('info') }}
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
