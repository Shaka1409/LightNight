<!-- Banner -->
@if (request()->is('/') || request()->is('home'))
    <div class="relative bg-gradient-to-br from-amber-200/30 via-transparent to-orange-300/30 w-full">
        <img src="{{ asset('storage/' . $banners[1]->image) }}" class="w-full h-32 sm:h-40 md:h-52 lg:h-64 object-cover" alt="Banner">
        <div class="absolute inset-0 flex flex-col justify-center items-center bg-black/50 px-4 text-center">
            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-extrabold text-white">
                Chào mừng đến với LIGHT9
            </h1>
            <p class="mt-2 sm:mt-3 md:mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-white">
                Khám phá những chiếc đèn ngủ đẹp nhất cho ngôi nhà của bạn
            </p>
        </div>
    </div>
@endif
<!-- Navigation -->
<nav class="bg-gray-100/90 p-4 shadow-md z-30 sticky top-0 rounded-sm">
    <div class="container mx-auto flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center mr-2">
            <img src="/image/logo.png" alt="Logo"
                class="w-16 h-13 sm:w-20 sm:h-15 md:w-22 md:h-17 lg:w-22 lg:h-17 object-contain">
        </a>

        <!-- Desktop Menu -->
        <ul id="menu" class="max-lg:hidden flex space-x-6 text-gray-700 items-center">
            <li>
                <a href="{{ asset('home') }}"
                    class="sm:hover:text-gray-900 font-medium min-w-[100px] text-center whitespace-nowrap {{ strpos(url()->current(), 'home') == true ? 'text-blue-500 font-bold' : '' }}">
                    Trang chủ</a>
            </li>
            <li>
                <a href="{{ asset('product') }}"
                    class="sm:hover:text-gray-900 font-medium min-w-[100px] text-center whitespace-nowrap {{ strpos(url()->current(), 'product') == true ? 'text-blue-500 font-bold' : '' }}">
                    Sản phẩm</a>
            </li>
            <li>
                <a href="{{ asset('about') }}"
                    class="sm:hover:text-gray-900 font-medium min-w-[100px] text-center whitespace-nowrap {{ strpos(url()->current(), 'about') == true ? 'text-blue-500 font-bold' : '' }}">
                    Giới thiệu</a>
            </li>
            <li>
                <a href="{{ asset('contact') }}"
                    class="mr-1 sm:hover:text-gray-900 font-medium min-w-[100px] text-center whitespace-nowrap {{ strpos(url()->current(), 'contact') == true ? 'text-blue-500 font-bold' : '' }}">
                    Liên hệ
                </a>
            </li>
        </ul>

        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="flex space-x-2 w-full lg:w-auto items-center">
            <input type="search" name="query" placeholder="Tìm kiếm sản phẩm..."
                class="border border-gray-300 rounded-md px-2 py-1 w-50 w-full md:w-40  lg:w-56 flex-grow focus:outline-none focus:ring focus:ring-green-300">
            <button type="submit"
                class="hidden md:block bg-green-500 text-white px-4 py-1 min-w-[100px] rounded-md sm:hover:bg-green-600">
                Tìm kiếm
            </button>
            <button type="submit" class="md:hidden text-gray-700 text-2xl">
                <i class="fas fa-search"></i>
            </button>
        </form>
@auth
    <a href="{{ asset('cart') }}"
        class="relative cart-link mr-1 ml-3 sm:hover:text-gray-900 font-medium {{ strpos(url()->current(), 'cart') == true ? 'text-blue-500 font-bold' : '' }}">
        <!-- Icon giỏ hàng -->
        <svg class="inline-block w-6 h-6 mr-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7H18a1 1 0 100-2H8.1M7 13L5.4 5M16 21a1 1 0 11-2 0 1 1 0 012 0zm-6 0a1 1 0 11-2 0 1 1 0 012 0z">
            </path>
        </svg>
        <!-- Badge số lượng -->
        @if(session('cart_count', 0) > 0)
            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs font-bold rounded-full px-1 py-0.5 leading-none">
                {{ session('cart_count') }}
            </span>
        @endif
    </a>
@endauth

        @auth
            <div class="flex justify-end z-20 ml-1">
                <div class="relative inline-block text-left">
                    <button id="logoutButton" type="button"
                        class="inline-flex items-center justify-center w-auto px-2 py-1 rounded-md border border-gray-300 shadow-sm bg-white text-xs font-medium text-gray-700 sm:hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition sm:px-4 sm:py-2 sm:text-sm">
                        {{ auth()->user()->name }}
                        <svg class="ml-1 h-4 w-4 sm:h-5 sm:w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown menu, ẩn mặc định -->
                    <div id="logoutDropdown"
                        class="origin-top-right absolute right-0 mt-2 w-40 sm:w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden transition ease-in-out duration-200"
                        role="menu" aria-orientation="vertical" aria-labelledby="logoutButton">
                        <div class="py-1" role="none">
                            <a href="{{ route('profile') }}"
                                class="block px-3 py-1 text-xs sm:text-sm text-gray-700 font-medium sm:hover:bg-gray-100 transition"
                                role="menuitem">
                                {{ __('Trang cá nhân') }}
                            </a>
                            <a href="{{ route('user.orders') }}"
                                class="block px-3 py-1 text-xs sm:text-sm text-gray-700 font-medium sm:hover:bg-gray-100 transition"
                                role="menuitem">
                                {{ __('Đơn hàng đã đặt') }}
                            </a>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="block px-3 py-1 text-xs sm:text-sm text-gray-700 font-medium sm:hover:bg-gray-100 transition"
                                role="menuitem">
                                {{ __('Đăng xuất') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            <script>
                document.getElementById('logoutButton').addEventListener('click', function() {
                    var dropdown = document.getElementById('logoutDropdown');
                    dropdown.classList.toggle('hidden');
                });
            </script>
        @endauth

        <!-- Auth Buttons for Desktop -->
        <div class="hidden md:flex space-x-2">
            @guest
                <a href="{{ url('login') }}"
                    class="bg-green-500 ml-2 text-white px-5 py-1 text-sm rounded-md min-w-[110px] text-center sm:hover:bg-green-600">Đăng
                    nhập</a>
                <a href="{{ url('register') }}"
                    class="bg-green-500 text-white px-5 py-1 text-sm rounded-md min-w-[105px] text-center sm:hover:bg-green-600">Đăng
                    kí</a>
            @endguest
        </div>

        <!-- Auth Icons for Mobile -->
        <div class="md:hidden flex space-x-4">
            @guest
                <a href="{{ url('login') }}" class="text-green-500 ml-2 text-2xl sm:hover:text-green-600">
                    <i class="fas fa-user"></i>
                </a>
                <a href="{{ url('register') }}" class="text-green-500 text-2xl sm:hover:text-green-600">
                    <i class="fas fa-user-plus"></i>
                </a>
            @endguest
        </div>
        <!-- Mobile Menu Button -->
        <button id="menu-toggle" class="lg:hidden text-gray-700 focus:outline-none mx-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- Backdrop tối mờ khi mở menu -->
<div id="mobile-menu-backdrop"
class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 ease-in-out hidden z-20">
</div>


    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="lg:hidden fixed top-0 right-0 w-2/4 sm:w-1/3 h-full bg-white/80 backdrop-blur-lg shadow-lg p-6 space-y-6 flex flex-col items-center pt-20 transform translate-x-full transition-transform duration-300 ease-in-out z-50 rounded-l-xl">

        <!-- Nút đóng -->
        <button id="close-menu" class="absolute top-4 right-4 text-gray-700 sm:hover:text-red-500 transition-all">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </button>

        <!-- Menu items -->
        <div class="flex flex-col space-y-4">

        <a href="/" class="menu-item {{ request()->is('home') ? 'text-blue-500 font-bold' : '' }}">
            <i class="fa-solid fa-house text-lg"></i> Trang chủ
        </a>
        <a href="/product" class="menu-item {{ request()->is('product') ? 'text-blue-500 font-bold' : '' }}">
            <i class="fa-solid fa-box text-lg"></i> Sản phẩm
        </a>
        <a href="/about" class="menu-item {{ request()->is('about') ? 'text-blue-500 font-bold' : '' }}">
            <i class="fa-solid fa-circle-info text-lg"></i> Giới thiệu
        </a>
        <a href="/contact" class="menu-item {{ request()->is('contact') ? 'text-blue-500 font-bold' : '' }}">
            <i class="fa-solid fa-address-book text-lg"></i> Liên hệ
        </a>
        <a href="/cart" class="cart-link menu-item {{ request()->is('cart') ? 'text-blue-500 font-bold' : '' }}">
            <i class="fa-solid fa-shopping-cart text-lg"></i> Giỏ hàng
        </a>
        </div>
    </div>

    <style>
        /* Tùy chỉnh menu item */
        .menu-item {
            @apply text-gray-700 text-lg font-medium flex items-center px-4 py-2 transition-all duration-300;
        }

        .menu-item:hover {
            @apply text-blue-600 scale-105;
        }
    </style>

</nav>

<!-- Mobile Menu Toggle Script -->
<script>
    const menuToggle = document.getElementById("menu-toggle");
    const closeMenu = document.getElementById("close-menu");
    const mobileMenu = document.getElementById("mobile-menu");
    const mobileMenuBackdrop = document.getElementById("mobile-menu-backdrop");

    function toggleMenu() {
        const isHidden = mobileMenu.classList.contains("hidden");
        if (isHidden) {
            mobileMenu.classList.remove("hidden");
            mobileMenuBackdrop.classList.remove("hidden");
            setTimeout(() => {
                mobileMenu.classList.remove("translate-x-full");
                mobileMenuBackdrop.classList.remove("bg-black/0");
                mobileMenuBackdrop.classList.add("bg-black/50");
            }, 10); // Delay nhỏ để animation hoạt động
        } else {
            mobileMenu.classList.add("translate-x-full");
            mobileMenuBackdrop.classList.remove("bg-black/50");
            mobileMenuBackdrop.classList.add("bg-black/0");
            setTimeout(() => {
                mobileMenu.classList.add("hidden");
                mobileMenuBackdrop.classList.add("hidden");
            }, 300); // Thời gian khớp với duration của animation
        }
    }

    menuToggle.addEventListener("click", toggleMenu);
    closeMenu.addEventListener("click", toggleMenu);
    mobileMenuBackdrop.addEventListener("click", toggleMenu);

    // Lấy trạng thái đăng nhập từ backend (true hoặc false)
    var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    // Lắng nghe sự kiện click của các liên kết có class "cart-link"
    document.querySelectorAll('.cart-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!isAuthenticated) {
                e.preventDefault(); // Ngăn hành động mặc định
                alert("Vui lòng đăng nhập để vào giỏ hàng");
            }
        });
    });
</script>
