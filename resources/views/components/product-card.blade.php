    <div class="relative">
        <img loading="lazy" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
            class="w-full h-56 object-cover transition-transform duration-500 sm:hover:scale-110">
        @if ($product->sale_price && $product->sale_price < $product->price)
            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                Giảm {{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) }}%
            </span>
        @endif
    </div>
    <div class="p-5">
        <h3 class="text-xl font-bold text-gray-900 capitalize truncate">{{ $product->name }}</h3>
        <p class="text-gray-700 text-sm mt-2 whitespace-nowrap">
            Giá:
            @if ($product->sale_price && $product->sale_price < $product->price)
                <span class="line-through text-red-500 mr-2">
                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                </span>
                <span class="font-semibold text-indigo-600 capitalize truncate">
                    {{ number_format($product->sale_price, 0, ',', '.') }} VNĐ
                </span>
            @else
                <span class="font-semibold text-indigo-600 capitalize truncate">
                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                </span>
            @endif
        </p>
        <p class="text-gray-500 text-xs mt-1 italic">
            Danh mục: {{ mb_convert_case($product->category->name, MB_CASE_TITLE, 'UTF-8') }}
        </p>
        <div class="flex flex-row gap-3 mt-4">
            @if ($product->stock_quantity > 0)
                <form class="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg sm:hover:bg-blue-700 transition-all duration-200"
                        title="Thêm vào giỏ">
                        <i class="fa-solid fa-cart-plus"></i>
                    </button>
                </form>
                <form class="buy-now-form flex-1" action="{{ route('checkout.buyNow') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                        class="flex-1 w-full px-4 py-2 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-lg sm:hover:from-green-600 sm:hover:to-teal-600 transition-all duration-200"
                        title="Mua ngay">
                        Mua ngay
                    </button>
                </form>
            @else
                <p class="text-red-600 font-semibold mt-4">Hết hàng</p>
            @endif
        </div>
        <button class="mt-4 w-full bg-indigo-600 text-white px-4 py-2 rounded-lg sm:hover:bg-indigo-700 transition-all duration-300 openModal"
            data-modal-id="productModal{{ $product->id }}">
            Chi tiết
        </button>
    </div>
