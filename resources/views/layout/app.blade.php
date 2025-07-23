<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Gọi các file CSS/JS thông qua Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- AOS Library -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="images/logo.ico">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

</head>

<body class="bg-gradient-to-br from-amber-200/80 via-transparent to-orange-300/80">
    @include('layout.header')


    @yield('content')
    {{-- Thông báo thành công --}}
    @if (session('success'))
        <div id="toast-success"
            class="fixed top-5 right-5 z-50 flex items-center justify-between px-4 py-3 bg-green-500 text-white rounded-lg shadow-lg space-x-4 animate-fade-in">
            <span>{{ session('success') }}</span>
            <button class="font-bold" onclick="closeToast('toast-success')">&times;</button>
        </div>
    @endif

    {{-- Thông báo lỗi --}}
    @if (session('error') || $errors->any())
        <div id="toast-error"
            class="fixed top-5 right-5 z-50 flex items-center justify-between px-4 py-3 bg-red-500 text-white rounded-lg shadow-lg space-x-4 animate-fade-in">
            <span>{{ session('error') ?? $errors->first() }}</span>
            <button class="font-bold" onclick="closeToast('toast-error')">&times;</button>
        </div>
    @endif



    @yield('modals')

    @include('layout.footer')

    <script src="/js/app.js"></script>

    @yield('scripts')


    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            offset: 20,
            duration: 400,
            once: true,
        });
    </script>

    <!-- Nút Lên đầu trang -->
    <button id="backToTop"
        class="fixed bottom-6 right-6 z-49 hidden p-3 rounded-full bg-blue-400/70 text-white shadow-lg sm:hover:bg-blue-500 transition duration-300">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <script>
        function closeToast(id) {
            const toast = document.getElementById(id);
            if (toast) {
                toast.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => toast.remove(), 300); // Gỡ khỏi DOM sau hiệu ứng
            }
        }

        // Tự động ẩn sau 3s
        window.addEventListener('DOMContentLoaded', () => {
            ['toast-success', 'toast-error'].forEach(id => {
                const toast = document.getElementById(id);
                if (toast) {
                    setTimeout(() => closeToast(id), 3000);
                }
            });
        });
        document.addEventListener("DOMContentLoaded", () => {
            const backToTopBtn = document.getElementById("backToTop");

            // Hiện/ẩn nút khi cuộn
            window.addEventListener("scroll", () => {
                if (window.scrollY > 300) {
                    backToTopBtn.classList.remove("hidden");
                } else {
                    backToTopBtn.classList.add("hidden");
                }
            });

            // Cuộn lên đầu khi bấm nút
            backToTopBtn.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        });
    </script>

</body>

</html>
