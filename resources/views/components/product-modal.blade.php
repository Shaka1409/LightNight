<div class="bg-white w-full px-4 sm:px-6 md:px-8 max-w-3xl mx-auto my-4 rounded-lg shadow-lg relative"
    style="max-height: 90vh; overflow:auto;">
    <!-- Nút đóng modal -->
    <button class="closeModal absolute top-2 right-2 text-gray-500 sm:hover:text-gray-700 text-3xl z-50"
        data-modal-id="productModal{{ $product->id }}">
        ×
    </button>
    <!-- Nội dung modal -->
    <div class="p-4 md:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Hình ảnh sản phẩm -->
            <div class="w-4/5 sm:w-full mx-auto">
                <img loading="lazy" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-full h-64 sm:h-80 sm:w-80 object-cover rounded-lg shadow-md transition duration-500 sm:hover:scale-105">
            </div>
            <!-- Thông tin sản phẩm & Thêm vào giỏ -->
            <div class="space-y-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 capitalize whitespace-normal line-clamp">
                    {{ $product->name }}</h2>
                @if ($product->sale_price && $product->sale_price < $product->price)
                    <p class="text-red-500 text-xl md:text-2xl font-bold">
                        <del class="mr-2 text-gray-500 text-m md:text-xl">{{ number_format($product->price, 0, ',', '.') }}
                            VNĐ</del>
                        {{ number_format($product->sale_price, 0, ',', '.') }} VNĐ
                    </p>
                @else
                    <p class="text-red-500 text-xl md:text-2xl font-bold">
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </p>
                @endif
                <p class="text-gray-700 text-sm"><strong>Danh mục:</strong>
                    {{ mb_convert_case($category->name, MB_CASE_TITLE, 'UTF-8') }}</p>
                <p class="text-gray-700 text-sm"><strong>Màu sắc:</strong>
                    {{ mb_convert_case($product->color, MB_CASE_TITLE, 'UTF-8') }}</p>
                <p class="text-gray-700 text-sm"><strong>Chất liệu:</strong>
                    {{ mb_convert_case($product->material, MB_CASE_TITLE, 'UTF-8') }}</p>
                <p class="text-gray-700 text-sm"><strong>Kích thước:</strong>
                    {{ mb_convert_case($product->size, MB_CASE_TITLE, 'UTF-8') }}</p>
                <!-- Phần chọn số lượng trong modal -->
                <div class="flex items-center space-x-3">
                    @if ($product->stock_quantity > 0)
                        <span class="text-gray-700 font-medium text-sm">Số lượng:</span>
                        <div class="flex items-center bg-gray-100 rounded-full shadow-sm">
                            <button type="button"
                                class="decrease-quantity w-10 h-10 flex items-center justify-center text-gray-600 sm:hover:bg-gray-200 rounded-l-full transition duration-200"
                                data-product-id="{{ $product->id }}" data-scope="modal"     onclick="updateQuantity(this, -1, 'modal')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                            </button>
                            <input type="number" name="quantity" id="quantity_modal_{{ $product->id }}" value="1" min="1"
                                max="{{ $product->stock_quantity }}"
                                class="w-16 bg-transparent text-center text-gray-800 font-semibold focus:outline-none"
                                readonly>
                            <button type="button"
                                class="increase-quantity w-10 h-10 flex items-center justify-center text-gray-600 sm:hover:bg-gray-200 rounded-r-full transition duration-200"
                                data-product-id="{{ $product->id }}" data-scope="modal"     onclick="updateQuantity(this, 1, 'modal')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                </div>
                <div class="flex sm:flex-row gap-4">
                    <!-- Form Thêm vào giỏ hàng -->
                    <form class="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="cart_quantity_modal_{{ $product->id }}" name="quantity"
                            value="1">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-full sm:hover:bg-blue-700 transition-all duration-300 shadow-md sm:hover:shadow-lg transform sm:hover:-translate-y-0.5">
                            Thêm vào giỏ hàng
                        </button>
                    </form>
                    <!-- Form Mua ngay -->
                    <form class="buy-now-form" action="{{ route('checkout.buyNow') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="checkout_quantity_modal_{{ $product->id }}" name="quantity"
                            value="1">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-full sm:hover:from-green-600 sm:hover:to-teal-600 transition-all duration-300 shadow-md sm:hover:shadow-lg transform sm:hover:-translate-y-0.5">
                            Mua ngay
                        </button>
                    </form>
                @else
                    <p class="text-red-600 font-semibold mt-2">Hết hàng</p>
                    @endif
                </div>
                <a href="{{ route('product.detail', \Illuminate\Support\Str::slug($product->name)) }}"
                    class="mt-2 mx-auto w-fit inline-block text-center bg-yellow-500 text-white px-4 py-2 rounded-lg sm:hover:bg-yellow-600 transition-all duration-300 shadow-md sm:hover:shadow-lg transform sm:hover:-translate-y-0.5">
                    Xem chi tiết sản phẩm
                </a>
            </div>
        </div>
    </div>
</div>
