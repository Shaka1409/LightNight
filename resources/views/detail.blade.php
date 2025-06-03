@extends('layout.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Nội dung modal -->
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Hình ảnh sản phẩm -->
                <div class="w-full max-w-2xl mx-auto">
                    {{-- Ảnh chính --}}
                    <div class="w-full">
                        <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            class="w-[70%] mx-auto h-70 md:h-80 object-cover rounded-xl shadow-lg">
                    </div>

                    {{-- Ảnh phụ (nếu có) --}}
                    @if ($product->image || ($product->images && $product->images->count()))
                        <div class="mt-4">
                            <div class="flex justify-center flex-wrap gap-3">
                                {{-- Thêm ảnh chính vào danh sách ảnh phụ --}}
                                <img loading="lazy" src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}" data-image="{{ asset('storage/' . $product->image) }}"
                                    onclick="changeMainImage(this)"
                                    class="w-24 h-24 object-cover rounded-lg shadow-sm transition duration-300 sm:hover:scale-105 cursor-pointer border-2 border-blue-500">

                                {{-- Hiển thị các ảnh phụ khác --}}
                                @foreach ($product->images as $image)
                                    <img loading="lazy" src="{{ asset('storage/' . $image->image_path) }}"
                                        alt="{{ $product->name }}" data-image="{{ asset('storage/' . $image->image_path) }}"
                                        onclick="changeMainImage(this)"
                                        class="w-24 h-24 object-cover rounded-lg shadow-sm transition duration-300 sm:hover:scale-105 cursor-pointer">
                                @endforeach
                            </div>
                        </div>
                    @endif

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
                        {{ mb_convert_case($product->category->name, MB_CASE_TITLE, 'UTF-8') }}</p>
                    <p class="text-gray-700 text-sm"><strong>Màu sắc:</strong>
                        {{ mb_convert_case($product->color, MB_CASE_TITLE, 'UTF-8') }}</p>
                    <p class="text-gray-700 text-sm"><strong>Chất liệu:</strong>
                        {{ mb_convert_case($product->material, MB_CASE_TITLE, 'UTF-8') }}</p>
                    <p class="text-gray-700 text-sm"><strong>Kích thước:</strong>
                        {{ mb_convert_case($product->size, MB_CASE_TITLE, 'UTF-8') }}</p>
                    <p class="text-gray-600 text-sm md:text-base capitalize  whitespace-normal line-clamp">
                        {{ $product->description }}
                    </p>
                    <!-- Phần chọn số lượng trong modal -->
                    @if ($product->stock_quantity > 0)
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-700 font-medium text-sm">Số lượng:</span>
                            <div class="flex items-center bg-gray-100 rounded-full shadow-sm">
                                <button type="button"
                                    class="decrease-quantity w-10 h-10 flex items-center justify-center text-gray-600 sm:hover:bg-gray-200 rounded-l-full transition duration-200"
                                    data-product-id="{{ $product->id }}" data-scope="modal">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                        </path>
                                    </svg>
                                </button>
                                <input type="number" id="quantity_modal_{{ $product->id }}" value="1" min="1"
                                    max="{{ $product->stock_quantity }}"
                                    class="w-16 bg-transparent text-center text-gray-800 font-semibold focus:outline-none"
                                    readonly>
                                <button type="button"
                                    class="increase-quantity w-10 h-10 flex items-center justify-center text-gray-600 sm:hover:bg-gray-200 rounded-r-full transition duration-200"
                                    data-product-id="{{ $product->id }}" data-scope="modal">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
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
            </div>
        </div>
        <!-- Phần Bình luận -->
        <div class="mt-6 border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Bình luận</h3>
            @if (auth()->check())
                <form action="{{ route('comments.store') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <textarea name="comment" rows="3" placeholder="Viết bình luận của bạn..."
                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-green-300">{{ old('comment') }}</textarea>
                    <button type="submit"
                        class="mt-2 bg-blue-600 text-white px-4 py-2 rounded sm:hover:bg-blue-700 transition">
                        Gửi bình luận
                    </button>
                </form>
            @else
                <p class="text-gray-600">
                    Bạn cần <a href="{{ route('login') }}" class="text-blue-500 underline">đăng nhập</a>
                    để bình luận.
                </p>
            @endif
            <div class="comments-list space-y-6">
                @foreach ($product->comments->whereIn('status', [2, 3]) as $comment)
                    <div
                        class="comment p-6 bg-white shadow rounded-lg border border-gray-200 sm:hover:shadow-lg transition duration-300">
                        <div class="comment-header flex justify-between items-center">
                            <span class="font-bold text-gray-800">{{ $comment->user->name }}</span>
                            <span class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="comment-body mt-3 text-gray-700 leading-relaxed">
                            {{ $comment->comment }}
                        </div>
                        @if (auth()->check() && auth()->id() === $comment->user_id)
                            <div class="mt-4 text-right">
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 sm:hover:underline font-medium">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Phần Chia sẻ Sản phẩm -->
        <div class="mt-6 border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Chia sẻ:</h3>
            <div class="flex justify-center gap-4">
                <!-- Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-full sm:hover:bg-gray-300 transition"
                    title="Chia sẻ Facebook">
                    <i class="fab fa-facebook-f text-xl"></i>
                </a>

                <!-- Twitter -->
                <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}"
                    target="_blank" rel="noopener noreferrer"
                    class="flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-full sm:hover:bg-gray-300 transition"
                    title="Chia sẻ Twitter">
                    <i class="fab fa-twitter text-xl"></i>
                </a>

                <!-- Pinterest -->
                <a href="https://pinterest.com/pin/create/button/?url={{ urlencode($shareUrl) }}&media={{ urlencode($shareImage) }}&description={{ urlencode($shareDescription) }}"
                    target="_blank" rel="noopener noreferrer"
                    class="flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-full sm:hover:bg-gray-300 transition"
                    title="Chia sẻ Pinterest">
                    <i class="fab fa-pinterest-p text-xl"></i>
                </a>

                <!-- WhatsApp -->
                <a href="https://api.whatsapp.com/send?text={{ urlencode($shareTitle . ' ' . $shareUrl) }}"
                    target="_blank" rel="noopener noreferrer"
                    class="flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-full sm:hover:bg-gray-300 transition"
                    title="Chia sẻ WhatsApp">
                    <i class="fab fa-whatsapp text-xl"></i>
                </a>

                <!-- Sao chép link -->
                <button type="button" onclick="copyLink('{{ $shareUrl }}')"
                    class="flex items-center px-3 py-2 bg-gray-200 text-gray-700 rounded-full sm:hover:bg-gray-300 transition"
                    title="Sao chép link">
                    <i class="fas fa-link text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Hàm updateQuantity với scope
        function updateQuantity(productId, delta, scope) {
            let displayInput, cartInput, checkoutInput;

            if (scope === 'modal') {
                displayInput = document.getElementById(`quantity_modal_${productId}`);
                cartInput = document.getElementById(`cart_quantity_modal_${productId}`);
                checkoutInput = document.getElementById(`checkout_quantity_modal_${productId}`);
            } else {
                displayInput = document.getElementById(
                    `quantity_outside_${productId}`); // Không dùng ngoài modal nên để null
                cartInput = document.getElementById(`cart_quantity_outside_${productId}`);
                checkoutInput = document.getElementById(`checkout_quantity_outside_${productId}`);
            }

            if (!displayInput && !cartInput && !checkoutInput) {
                console.error(`Inputs not found for product ${productId} in scope ${scope}`);
                return;
            }

            let currentValue = displayInput ? parseInt(displayInput.value) : parseInt(cartInput.value);
            let maxValue = parseInt(displayInput.getAttribute('max')) || Infinity; // Lấy giá trị max từ thuộc tính input
            let newValue = currentValue + delta;
            if (newValue > maxValue) newValue = maxValue;
            if (newValue < 1) newValue = 1;

            if (displayInput) displayInput.value = newValue;
            cartInput.value = newValue;
            checkoutInput.value = newValue;
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Thiết lập biến xác định người dùng đã đăng nhập hay chưa
            var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

            // Gắn sự kiện cho nút tăng/giảm số lượng
            document.querySelectorAll('.increase-quantity').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const scope = this.getAttribute('data-scope') || 'outside';
                    updateQuantity(productId, 1, scope);
                });
            });

            document.querySelectorAll('.decrease-quantity').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const scope = this.getAttribute('data-scope') || 'outside';
                    updateQuantity(productId, -1, scope);
                });
            });

            // Xử lý form "Thêm vào giỏ hàng" (ngoài và trong modal)
            var addToCartForms = document.querySelectorAll('.add-to-cart-form');
            addToCartForms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!isAuthenticated) {
                        alert("Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!");
                        return;
                    }
                    let formData = new FormData(form);
                    fetch(form.action, {
                            method: "POST",
                            headers: {
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Network response was not ok");
                            }
                            return response.json();
                        })
                        .then(data => {
                            alert(data.message ||
                                "Sản phẩm đã được thêm vào giỏ hàng thành công!");
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("Có lỗi khi thêm sản phẩm vào giỏ hàng.");
                        });
                });
            });

            // Xử lý form "Mua ngay" (ngoài và trong modal)
            var buyNowForms = document.querySelectorAll('.buy-now-form');
            buyNowForms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    if (!isAuthenticated) {
                        e.preventDefault();
                        alert("Vui lòng đăng nhập để mua hàng!");
                        return;
                    }
                    // Nếu đã đăng nhập, form sẽ submit bình thường
                });
            });
        });
        // Hàm thay đổi ảnh chính khi click vào ảnh phụ
        function changeMainImage(element) {
            const mainImage = document.getElementById('mainImage');
            const newSrc = element.getAttribute('data-image');
            if (mainImage && newSrc) {
                mainImage.src = newSrc;
            }
        }


        // Hàm sao chép link
        function copyLink(link) {
            if (navigator.clipboard && window.isSecureContext) {
                // Clipboard API hiện đại
                navigator.clipboard.writeText(link)
                    .then(() => {
                        showNotification("Link đã được sao chép!");
                    })
                    .catch(() => {
                        fallbackCopyTextToClipboard(link);
                    });
            } else {
                // Fallback cho trình duyệt cũ
                fallbackCopyTextToClipboard(link);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            // Không hiển thị textarea
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                showNotification(successful ? "Link đã được sao chép!" : "Không thể sao chép link. Vui lòng thử lại!");
            } catch (err) {
                showNotification("Không thể sao chép link. Vui lòng thử lại!");
            }

            document.body.removeChild(textArea);
        }

        function showNotification(message) {
            const notification = document.createElement('div');
            notification.innerText = message;
            notification.style.position = 'fixed';
            notification.style.bottom = '20px';
            notification.style.right = '20px';
            notification.style.backgroundColor = 'rgba(0,0,0,0.8)';
            notification.style.color = '#fff';
            notification.style.padding = '10px 20px';
            notification.style.borderRadius = '5px';
            notification.style.zIndex = 9999;
            notification.style.opacity = 0;
            notification.style.transition = 'opacity 0.5s';

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = 1;
            }, 100);

            setTimeout(() => {
                notification.style.opacity = 0;
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 2000);
        }
    </script>
@endsection
