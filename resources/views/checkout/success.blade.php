<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-100 min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white shadow-xl rounded-xl max-w-xl w-full p-8 text-center">
        <!-- Icon thành công -->
        <div class="mx-auto mb-6">
            <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto shadow">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <!-- Lời cảm ơn -->
        <h1 class="text-3xl font-bold text-green-700 mb-2">Đặt hàng thành công!</h1>
        <p class="text-gray-700 text-lg mb-6">Cảm ơn quý khách đã tin tưởng <span class="text-blue-600 font-semibold">Light9</span>.</p>

        <!-- Mã đơn hàng -->
        <div class="mb-4">
            <span class="text-gray-600 font-medium">Mã đơn hàng:</span>
            <div class="inline-block bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xl font-bold py-2 px-4 rounded-full shadow">
                #{{ session('orderId') }}
            </div>
        </div>

        <!-- Tổng tiền -->
        <div class="mb-8">
            <span class="text-gray-600 font-medium">Tổng tiền:</span>
            <div class="text-2xl text-red-600 font-bold">
                {{ number_format(session('total', 0), 0, ',', '.') }} VND
            </div>
        </div>

        <!-- Nút trở về -->
        <a href="{{ url('/') }}"
           class="inline-block bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg sm:hover:bg-blue-600 transition">
            Về trang chủ
        </a>
    </div>

</body>
</html>
