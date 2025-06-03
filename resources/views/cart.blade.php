@extends('layout.app')

@section('content')
    <div class="container mx-auto px-6 py-12 ">
        <h1 class="text-xl md:text-3xl text-center font-bold font-serif mb-5 text-blue-500">
            Cảm ơn quý khách vì đã tin tưởng Light9
        </h1>

        <div class="w-full max-w-4xl mx-auto">
            @if (empty($cartItems))
                <div class="text-center py-12">
                    <h2 class="text-xl md:text-2xl font-bold">Giỏ hàng đang trống</h2>
                    <p class="text-gray-600">Hãy thêm sản phẩm vào giỏ hàng của bạn.</p>
                    <a href="{{ url('/product') }}"
                        class="mt-4 inline-block bg-blue-500 text-white px-6 py-3 rounded-md sm:hover:bg-blue-600 transition duration-300 font-semibold">
                        Khám phá ngay
                    </a>
                </div>
            @else
                <h1 class="text-3xl font-bold text-center mb-4">Giỏ Hàng Của Bạn</h1>
                <p class="text-gray-600 text-center mb-6">Kiểm tra các sản phẩm trước khi đặt hàng</p>

                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr class="border-b">
                                <th class="py-3 px-4">Sản phẩm</th>
                                <th class="py-3 px-4">Giá</th>
                                <th class="py-3 px-4">Số lượng</th>
                                <th class="py-3 px-4">Tổng</th>
                                <th class="py-3 px-4">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr data-product-id="{{ $item['id'] }}" class="border-b sm:hover:bg-gray-50 transition">
                                    <td class="py-4 px-4 flex items-center">
                                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('fallback.png') }}"
                                            alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded mr-4">
                                        <span
                                            class="font-semibold">{{ mb_convert_case($item['name'], MB_CASE_TITLE, 'UTF-8') }}</span>
                                    </td>
                                    <td class="py-3 px-4 unit-price">{{ number_format($item['price'], 0, ',', '.') }} VND
                                    </td>
                                    <td class="py-3 px-4">
                                        @if (($item['stock_quantity'] ?? 0) == 0)
                                            <span class="text-red-500 font-semibold">Hết hàng</span>
                                        @else
                                            <input type="number" name="quantity[]"
                                                value="{{ min($item['quantity'], $item['stock_quantity']) }}" min="1"
                                                max="{{ $item['stock_quantity'] }}" data-price="{{ $item['price'] }}"
                                                class="quantity-input w-16 p-2 border rounded text-center">
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 total-cell">
                                        {{ number_format($item['price'] * min($item['quantity'], $item['stock_quantity']), 0, ',', '.') }}
                                        VND
                                    <td class="py-3 px-4">
                                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 sm:hover:text-red-700">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card -->
                <div class="md:hidden space-y-4">
                    @foreach ($cartItems as $item)
                        <div class="border p-4 rounded-lg bg-white shadow flex flex-col sm:flex-row items-center sm:items-start gap-4"
                            data-product-id="{{ $item['id'] }}">
                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('fallback.png') }}"
                                alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded">
                            <div class="flex-1 text-center sm:text-left">
                                <p class="text-lg font-semibold">{{ $item['name'] }}</p>
                                <p class="text-gray-600 unit-price">{{ number_format($item['price'], 0, ',', '.') }} VND
                                </p>
                                <div class="flex justify-center sm:justify-start items-center gap-2 mt-2">
                                    @if (($item['stock_quantity'] ?? 0) == 0)
                                        <span class="text-red-500 font-semibold">Hết hàng</span>
                                    @else
                                        <input type="number" name="quantity[]" value="{{ $item['quantity'] }}"
                                            min="1" data-price="{{ $item['price'] }}"
                                            class="quantity-input w-14 sm:w-16 p-2 border rounded text-center">
                                        <p class="font-semibold text-gray-700 total-cell">=
                                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VND</p>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 sm:hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Tổng tiền -->
                <div id="cart-total" class="mt-6 text-right">
                    <p class="text-lg font-semibold">
                        Tổng tiền: <span class="text-red-500">{{ number_format($totalPrice, 0, ',', '.') }} VND</span>
                    </p>
                    <a href="{{ route('checkout.view') }}"
                        class="mt-4 inline-block bg-green-600 text-white px-6 py-3 rounded sm:hover:bg-green-700 transition">
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
                    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
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
                            document.querySelector('#cart-total span').textContent = data
                                .totalPrice;
                        })
                        .catch(error => console.error("Update Error:", error));
                });
            });

            updateCartTotal();
        });
    </script>
@endsection
