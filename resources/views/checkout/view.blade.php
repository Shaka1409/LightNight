@extends('layout.app')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold text-center mb-8 text-blue-600 md:text-3xl sm:text-2xl">
            Thông tin đặt hàng
        </h1>
        <h3 class="text-xl text-violet-600 font-serif text-center mt-3 md:text-lg sm:text-base">
            Kiểm tra kỹ thông tin của bạn trước khi đặt hàng
        </h3>

        @if (count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form thông tin giao hàng (col 1 và 2 trên mobile, col 1 trên desktop) -->
                <div class="lg:col-span-2">
                    <!-- Tóm tắt đơn hàng trên mobile -->
                    <div class="lg:hidden bg-white shadow-lg rounded-lg p-4 mb-6">
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">Tóm tắt đơn hàng</h2>
                        @foreach ($cartItems as $item)
                            <div class="flex justify-between items-center border-b pb-2 mb-2">
                                <div>
                                    <h3 class="font-bold text-gray-700">
                                        {{ mb_convert_case($item['name'], MB_CASE_TITLE, 'UTF-8') }}</h3>
                                    <p class="text-sm text-gray-500">Số lượng: {{ $item['quantity'] }}</p>
                                </div>
                                <p class="text-gray-700 font-semibold">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VND
                                </p>
                            </div>
                        @endforeach
                        <div class="text-right mt-4">
                            <p class="text-lg font-bold text-gray-800">
                                Tổng tiền: <span class="text-red-500">{{ number_format($totalPrice, 0, ',', '.') }}
                                    VND</span>
                            </p>
                        </div>
                    </div>

                    <!-- Form nhập thông tin -->
                    <form action="{{ route('checkout.confirm') }}" method="POST" enctype="multipart/form-data"
                        class="bg-white shadow-lg rounded-lg p-6">
                        @csrf
                        @if (request()->has('product_id') && request()->has('quantity'))
                            <input type="hidden" name="product_id" value="{{ request()->input('product_id') }}">
                            <input type="hidden" name="quantity" value="{{ request()->input('quantity') }}">
                        @endif

                        <h2 class="text-2xl font-semibold mb-6 text-gray-800 md:text-xl sm:text-lg">
                            Thông tin giao hàng
                        </h2>

                        @auth
                            @php
                                $defaultName = old('name', auth()->user()->name);
                                $defaultEmail = old('email', auth()->user()->email);
                                $defaultAddress = old('address', auth()->user()->address);
                                $defaultphonenumber = old('phonenumber', auth()->user()->phonenumber);
                            @endphp
                        @else
                            @php
                                $defaultName = old('name');
                                $defaultEmail = old('email');
                                $defaultAddress = old('address');
                                $defaultphonenumber = old('phonenumber');
                            @endphp
                        @endauth

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block font-semibold text-gray-700 mb-1">
                                Email
                            </label>
                            <input type="email" id="email" name="email" readonly
                                class="w-full p-2 border border-gray-300 bg-gray-200 rounded focus:outline-none focus:ring focus:ring-blue-300 sm:text-sm"
                                value="{{ $defaultEmail }}" placeholder="Nhập email">
                        </div>

                        <!-- Họ và tên -->
                        <div class="mb-4">
                            <label for="name" class="block font-semibold text-gray-700 mb-1">
                                Người nhận
                            </label>
                            <input type="text" id="name" name="name"
                                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300 sm:text-sm"
                                value="{{ $defaultName }}" placeholder="Nhập họ tên">
                        </div>

                        <!-- Khu vực giao hàng -->
                        <div class="mb-4">
                            <label for="shipping_area" class="block font-semibold text-gray-700 mb-1">
                                Khu vực giao hàng
                            </label>
                            <select id="shipping_area" name="shipping_area"
                                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300 sm:text-sm"
                                required>
                                <option value="hanoi" {{ old('shipping_area') == 'hanoi' ? 'selected' : '' }}>Nội thành Hà
                                    Nội (Free ship)</option>
                                <option value="mienbac" {{ old('shipping_area') == 'mienbac' ? 'selected' : '' }}>Các tỉnh
                                    miền Bắc (30k)</option>
                                <option value="toanquoc" {{ old('shipping_area') == 'toanquoc' ? 'selected' : '' }}>Toàn
                                    quốc (50k)</option>
                            </select>
                        </div>

                        <!-- Địa chỉ cụ thể -->
                        <div class="mb-4">
                            <label for="address" class="block font-semibold text-gray-700 mb-1">
                                Địa chỉ cụ thể
                            </label>
                            <input type="text" id="address" name="address"
                                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300 sm:text-sm"
                                value="{{ $defaultAddress }}" placeholder="Nhập số nhà, đường, phường/xã...">
                        </div>


                        <!-- Số điện thoại -->
                        <div class="mb-4">
                            <label for="phonenumber" class="block font-semibold text-gray-700 mb-1">
                                Số điện thoại
                            </label>
                            <input type="tel" id="phonenumber" name="phonenumber"
                                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300 sm:text-sm"
                                value="{{ $defaultphonenumber }}" placeholder="Nhập số điện thoại">
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="mb-6">
                            <label class="block font-semibold text-gray-700 mb-1">Phương thức thanh toán</label>
                            <label class="flex items-center mb-2">
                                <input type="radio" name="payment_method" value="cod" class="mr-2" checked
                                    onchange="toggleBankTransfer(false)">
                                <span>Thanh toán khi giao hàng (COD)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="bank" class="mr-2"
                                    onchange="toggleBankTransfer(true)">
                                <span>Chuyển khoản ngân hàng</span>
                            </label>
                        </div>
                        <!-- Chuyển khoản ngân hàng -->
                        <div id="bank-transfer-info" class="hidden mb-6">
                            <div class="bg-gray-100 p-4 rounded border border-blue-300">
                                <p class="text-sm mb-2">Quét mã QR hoặc chuyển khoản theo thông tin:</p>
                                <img src="{{ asset('images/qr-code.png') }}" alt="QR chuyển khoản" class="w-40 h-40 mb-3">

                                <p><strong>Ngân hàng:</strong> MBBank</p>
                                <p><strong>STK:</strong> 8140920048</p>
                                <p><strong>Chủ tài khoản:</strong> Nguyễn Thanh Trình</p>
                                <label for="payment_proof" class="block mt-4 font-semibold text-gray-700 mb-1">Tải ảnh xác
                                    nhận (1–2 ảnh)</label>
                                <input type="file" name="payment_proof[]" id="payment_proof" accept="image/*"
                                    multiple class="w-full border border-gray-300 rounded p-2 sm:text-sm">
                            </div>
                        </div>


                        <div class="text-right">
                            <button type="submit"
                                class="mt-4 bg-blue-600 text-white px-6 py-3 rounded sm:hover:bg-blue-700 transition sm:px-4 sm:py-2">
                                Đặt hàng
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tóm tắt đơn hàng (col 3 trên desktop, ẩn trên mobile) -->
                <div class="lg:col-span-1 hidden lg:block">
                    <div class="bg-white shadow-lg rounded-lg p-6 sticky top-12">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Đơn hàng ({{ count($cartItems) }} sản phẩm)
                        </h2>
                        @foreach ($cartItems as $item)
                            <div class="flex items-center mb-4">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('fallback.png') }}"
                                    alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded mr-4">
                                <div>
                                    <h3 class="font-bold text-gray-700">
                                        {{ mb_convert_case($item['name'], MB_CASE_TITLE, 'UTF-8') }}</h3>
                                    <p class="text-sm text-gray-500">Số lượng: {{ $item['quantity'] }}</p>
                                </div>
                                <p class="ml-auto text-lg font-semibold text-green-600">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VND
                                </p>
                            </div>
                        @endforeach
                        <div class="border-t pt-4">
                            <p class="text-lg font-bold text-gray-800">
                                Tổng tiền: <span class="text-red-500">{{ number_format($totalPrice, 0, ',', '.') }}
                                    VND</span>
                            </p>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            Phí vận chuyển: <span id="shipping-fee-text">0 VND</span>
                        </p>
                        <p class="text-xl font-bold text-gray-800 mt-2">
                            Thành tiền: <span class="text-red-500" id="total-with-shipping">
                                {{ number_format($totalPrice, 0, ',', '.') }} VND
                            </span>
                        </p>
                        @if (session('checkout_type') != 'buy_now')
                            <a href="{{ route('cart.index') }}">
                                <button
                                    class="mt-4 w-full bg-gray-200 text-gray-700 px-4 py-2 rounded sm:hover:bg-gray-300 sm:text-sm">
                                    Quay về giỏ hàng
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <p class="text-center text-gray-600 md:text-sm sm:text-xs">
                Giỏ hàng của bạn trống.
            </p>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        const baseTotal = {{ $totalPrice }};
        const shippingSelect = document.getElementById('shipping_area');
        const shippingFeeText = document.getElementById('shipping-fee-text');
        const totalWithShipping = document.getElementById('total-with-shipping');

        function formatCurrency(value) {
            return value.toLocaleString('vi-VN') + ' VND';
        }

        function updateShippingFee() {
            let shippingFee = 0;

            switch (shippingSelect.value) {
                case 'hanoi':
                    shippingFee = 0;
                    break;
                case 'mienbac':
                    shippingFee = 30000;
                    break;
                case 'toanquoc':
                    shippingFee = 50000;
                    break;
            }

            shippingFeeText.textContent = formatCurrency(shippingFee);
            totalWithShipping.textContent = formatCurrency(baseTotal + shippingFee);
        }

        function toggleBankTransfer(show) {
            const bankTransferInfo = document.getElementById('bank-transfer-info');
            bankTransferInfo.classList.toggle('hidden', !show);
        }

        // Gọi khi trang load
        document.addEventListener('DOMContentLoaded', updateShippingFee);

        // Gọi lại mỗi khi chọn khu vực khác
        shippingSelect.addEventListener('change', updateShippingFee);
    </script>
@endsection
