<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('image/bg-signup.jpg') }}');">
    @yield('content')
    @if(session('success'))
    <div id="toast" class="fixed top-5 right-5 bg-green-500 text-white px-4 mt-4 py-3 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 z-50 flex items-center justify-between space-x-4">
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
    <div id="errorToast" class="fixed top-5 right-5 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 z-50 flex items-center justify-between space-x-4">
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
</body>

</body>
</html>
