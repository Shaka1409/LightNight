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
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/logo.ico">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body class="bg-gradient-to-br from-amber-200/80 via-transparent to-orange-300/80">
    @include('layout.header')


    @yield('content')
    @if (session('success'))
        <div id="toast"
            class="fixed top-5 right-5 bg-green-500 text-white px-4 mt-4 py-3 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 z-50 flex items-center justify-between space-x-4">
            <span>{{ session('success') }}</span>
            <button id="close-toast" class="text-white font-bold focus:outline-none">&times;</button>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let toast = document.getElementById("toast");
                let closeBtn = document.getElementById("close-toast");

                // Hiển thị toast
                toast.classList.remove("opacity-0");
                toast.classList.add("opacity-100");

                // Ẩn sau 3 giây
                let timeout = setTimeout(() => {
                    toast.classList.remove("opacity-100");
                    toast.classList.add("opacity-0");
                }, 3000);

                // Bấm nút X để tắt ngay
                closeBtn.addEventListener("click", function() {
                    clearTimeout(timeout); // Hủy auto ẩn nếu bấm trước 3s
                    toast.classList.remove("opacity-100");
                    toast.classList.add("opacity-0");
                });
            });
        </script>
    @endif
    @if (session('error') || $errors->any())
        <div id="errorToast"
            class="fixed top-5 right-5 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 z-50 flex items-center justify-between space-x-4">
            <span>{{ session('error') ?? $errors->first() }}</span> <!-- Chỉ hiển thị lỗi đầu tiên -->
            <button id="close-error-toast" class="text-white font-bold focus:outline-none">&times;</button>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let toast = document.getElementById("errorToast");
                let closeBtn = document.getElementById("close-error-toast");

                // Hiển thị toast
                toast.classList.remove("opacity-0");
                toast.classList.add("opacity-100");

                // Ẩn sau 3 giây
                let timeout = setTimeout(() => {
                    toast.classList.remove("opacity-100");
                    toast.classList.add("opacity-0");
                }, 3000);

                // Bấm nút X để tắt ngay
                closeBtn.addEventListener("click", function() {
                    clearTimeout(timeout); // Hủy auto ẩn nếu bấm trước 3s
                    toast.classList.remove("opacity-100");
                    toast.classList.add("opacity-0");
                });
            });
        </script>
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
