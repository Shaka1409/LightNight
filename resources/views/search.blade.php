@extends('layout.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        @if ($products->isEmpty())
            <h1 class="text-xl sm:text-3xl font-bold text-center text-blue-500 mb-8 mt-8">
                Sản phẩm này hiện không có trong cửa hàng
            </h1>
            <br>
            <h1 class="text-xl sm:text-2xl font-bold text-center text-blue-500 mb-8 mt-8">
                Các sản phẩm nổi bật khác
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ">
                @foreach ($categories as $category)
                    @foreach ($category->products as $product)
                            <!-- Card sản phẩm -->
                            <div class="product-card bg-white shadow-lg rounded-xl overflow-hidden transition-transform duration-300 sm:hover:scale-105 sm:hover:shadow-2xl sm:hover:ring-2 sm:hover:ring-indigo-200 "
                                data-aos="fade-up"  data-aos-duration="400">
                                <x-product-card :product="$product" />
                            </div>
                    @endforeach
                @endforeach
            </div>
        @else
            <h1 class="text-xl sm:text-3xl font-bold text-center text-blue-500 mb-8 mt-8">
                Sản phẩm bạn muốn tìm
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up"  data-aos-duration="400">
                @foreach ($products as $product)
                    <!-- Card sản phẩm -->
                    <div
                        class="product-card bg-white shadow-lg rounded-xl overflow-hidden transition-transform duration-300 sm:hover:scale-105 sm:hover:shadow-2xl sm:hover:ring-2 sm:hover:ring-indigo-200">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@section('modals')
    @if ($products->isEmpty())
        @php
            $uniqueProducts = [];
            foreach ($categories->where('status', 1) as $category) {
                foreach ($category->products as $product) {
                    if ($product->status == 1 && !isset($uniqueProducts[$product->id])) {
                        $uniqueProducts[$product->id] = $product;
                    }
                }
            }
        @endphp
        @foreach ($uniqueProducts as $product)
            <div id="productModal{{ $product->id }}"
                class="fixed inset-0 w-screen h-screen hidden z-50 flex items-center justify-center bg-black bg-opacity-60 overflow-auto">
             <x-product-modal :product="$product" />
            </div>
        @endforeach
    @else
        @foreach ($products->whereIn('status', [0, 1]) as $product)
            <div id="productModal{{ $product->id }}"
                class="fixed inset-0 w-screen h-screen hidden z-50 flex items-center justify-center bg-black bg-opacity-60 overflow-auto">
                <x-product-modal :product="$product" />
            </div>
        @endforeach
    @endif
@endsection

@section('scripts')
    <script>
        // Hàm updateQuantity với scope để tách biệt modal và ngoài
        function updateQuantity(productId, delta, scope) {
            let displayInput, cartInput, checkoutInput;

            if (scope === 'modal') {
                displayInput = document.getElementById(`quantity_modal_${productId}`);
                cartInput = document.getElementById(`cart_quantity_modal_${productId}`);
                checkoutInput = document.getElementById(`checkout_quantity_modal_${productId}`);
            } else {
                displayInput = null; // Không có input hiển thị ngoài modal
                cartInput = document.getElementById(`cart_quantity_outside_${productId}`);
                checkoutInput = document.getElementById(`checkout_quantity_outside_${productId}`);
            }

            if (!cartInput || !checkoutInput) {
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

            // Gắn sự kiện cho nút tăng/giảm số lượng (chỉ trong modal)
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

            // Xử lý form "Thêm vào giỏ hàng"
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

            // Xử lý form "Mua ngay"
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

            // Xử lý mở/đóng modal
            const openModalButtons = document.querySelectorAll('.openModal');
            const closeModalButtons = document.querySelectorAll('.closeModal');

            openModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = button.getAttribute('data-modal-id');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden');
                    }
                });
            });

            closeModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = button.getAttribute('data-modal-id');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            });

            window.addEventListener('click', function(e) {
                document.querySelectorAll('[id^="productModal"]').forEach(modal => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            });
        });
    </script>
@endsection
