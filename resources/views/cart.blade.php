@extends('layout.app')

@section('content')
    <div class="container mx-auto px-6 py-12 bg-gradient-to-b from-gray-50 to-white">
        <h1 class="text-2xl md:text-4xl text-center font-extrabold font-serif mb-6 text-blue-600 bg-gradient-to-r from-blue-600 to-indigo-600 text-transparent bg-clip-text">
            Cảm ơn quý khách vì đã tin tưởng Light9
        </h1>

        <div class="w-full max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl p-6 md:p-8">
            @if (empty($cartItems))
                <div class="text-center py-16">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Giỏ hàng đang trống</h2>
                    <p class="text-gray-600 text-lg mb-6">Hãy thêm sản phẩm vào giỏ hàng của bạn để trải nghiệm mua sắm tuyệt vời!</p>
                    <a href="{{ url('/product') }}"
                        class="inline-block bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-8 py-4 rounded-xl sm:hover:from-blue-600 sm:hover:to-indigo-600 transition duration-300 font-semibold text-lg shadow-md">
                        Khám phá ngay
                    </a>
                </div>
            @else
                <h1 class="text-3xl md:text-4xl font-extrabold text-center mb-4 text-gray-900">Giỏ Hàng Của Bạn</h1>
                <p class="text-gray-600 text-center mb-8 text-lg">Kiểm tra các sản phẩm trước khi đặt hàng</p>

                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left border border-gray-100 rounded-2xl overflow-hidden bg-white shadow-lg">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr class="border-b border-gray-200">
                                <th class="py-4 px-6 font-semibold">Sản phẩm</th>
                                <th class="py-4 px-6 font-semibold">Giá</th>
                                <th class="py-4 px-6 font-semibold">Số lượng</th>
                                <th class="py-4 px-6 font-semibold">Tổng</th>
                                <th class="py-4 px-6 font-semibold">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr data-product-id="{{ $item['id'] }}" class="border-b sm:hover:bg-gray-50 transition duration-200">
                                    <td class="py-6 px-6 flex items-center">
                                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('fallback.png') }}"
                                            alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded-lg mr-4 shadow-sm">
                                        <span
                                            class="font-semibold text-lg">{{ mb_convert_case($item['name'], MB_CASE_TITLE, 'UTF-8') }}</span>
                                    </td>
                                    <td class="py-6 px-6 unit-price text-gray-700">{{ number_format($item['price'], 0, ',', '.') }} VND</td>
                                    <td class="py-6 px-6">
                                        @if (($item['stock_quantity'] ?? 0) == 0)
                                            <span class="text-red-500 font-semibold text-base">Hết hàng</span>
                                        @else
                                            <input type="number" name="quantity[]"
                                                value="{{ min($item['quantity'], $item['stock_quantity']) }}" min="1"
                                                max="{{ $item['stock_quantity'] }}" data-price="{{ $item['price'] }}"
                                                class="quantity-input w-20 p-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @endif
                                    </td>
                                    <td class="py-6 px-6 total-cell text-gray-700">
                                        {{ number_format($item['price'] * min($item['quantity'], $item['stock_quantity']), 0, ',', '.') }} VND
                                    </td>
                                    <td class="py-6 px-6">
                                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 sm:hover:text-red-600 transition">
                                                <i class="fa-solid fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card -->
                <div class="md:hidden space-y-6">
                    @foreach ($cartItems as $item)
                        <div class="border p-6 rounded-2xl bg-white shadow-lg flex flex-col sm:flex-row items-center sm:items-start gap-6"
                            data-product-id="{{ $item['id'] }}">
                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('fallback.png') }}"
                                alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg shadow-sm">
                            <div class="flex-1 text-center sm:text-left">
                                <p class="text-xl font-semibold text-gray-800">{{ $item['name'] }}</p>
                                <p class="text-gray-600 text-base unit-price">{{ number_format($item['price'], 0, ',', '.') }} VND</p>
                                <div class="flex justify-center sm:justify-start items-center gap-3 mt-3">
                                    @if (($item['stock_quantity'] ?? 0) == 0)
                                        <span class="text-red-500 font-semibold text-base">Hết hàng</span>
                                    @else
                                        <input type="number" name="quantity[]" ventana de chatvalue="{{ $item['quantity'] }}"
                                            min="1" data-price="{{ $item['price'] }}"
                                            class="quantity-input w-16 p-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <p class="font-semibold text-gray-700 total-cell text-base">= {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VND</p>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 sm:hover:text-red-600 transition">
                                    <i class="fa-solid fa-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Tổng tiền -->
                <div id="cart-total" class="mt-8 text-right">
                    <p class="text-xl font-semibold text-gray-800">
                        Tổng tiền: <span class="text-red-500">{{ number_format($totalPrice, 0, ',', '.') }} VND</span>
                    </p>
                    <a href="{{ route('checkout.view') }}"
                        class="mt-6 inline-block bg-gradient-to-r from-green-600 to-teal-600 text-white px-8 py-4 rounded-xl sm:hover:from-green-700 sm:hover:to-teal-700 transition duration-300 font-semibold text-lg shadow-md">
                        Đặt hàng
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function formatVND(number) {
                return new Intl.NumberFormat('vi-VN').format(number) + ' VND';
            }

            function updateCartTotal() {
                let total = 0;
                document.querySelectorAll('.total-cell').forEach(function(cell) {
                    if (!cell.offsetParent) return;
                    let text = cell.textContent.replace(/\D/g, "");
                    let cellValue = parseInt(text);
                    if (!isNaN(cellValue)) total += cellValue;
                });
                document.querySelector('#cart-total span').textContent = formatVND(total);
            }

            document.querySelectorAll('.quantity-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    let quantity = parseInt(this.value);
                    if (quantity < 1 || isNaN(quantity)) {
                        this.value = 1;
                        quantity = 1;
                    }

                    let unitPrice = parseFloat(this.getAttribute('data-price'));
                    let row = this.closest('[data-product-id]');
                    let totalCell = row.querySelector('.total-cell');
                    let newTotal = unitPrice * quantity;
                    totalCell.textContent = formatVND(newTotal);
                    updateCartTotal();

                    // Gửi AJAX cập nhật giỏ hàng
                    let productId = row.getAttribute('data-product-id');
                    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch("{{ route('cart.update') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: quantity
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            document.querySelector('#cart-total span').textContent = data.totalPrice;
                        })
                        .catch(error => console.error("Update Error:", error));
                });
            });

            updateCartTotal();
        });
    </script>
@endsection