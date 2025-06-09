@extends('layout.app')

@section('content')
    <div class="container mx-auto px-4 py-4">

        <div class="container w-5/6 mx-auto px-4 py-8 relative">
            <div class="absolute inset-0 -z-10"></div>
            <!-- Grid chính cho bố cục -->
            <div class="grid grid-cols-3 gap-4">
                @foreach ($bestSellingProducts as $index => $product)
                    @if ($index === 0)
                        <!-- Sản phẩm nổi bật: chiếm 2 cột -->
                        <div class="col-span-2 cursor-pointer relative aspect-square rounded-2xl overflow-hidden shadow-xl group sm:hover:shadow-2xl transition-all duration-300 transform sm:hover:ring-2 sm:hover:ring-indigo-200 openModal"
                            data-modal-id="productModal{{ $product->id }}" data-aos="fade-up" data-aos-duration="1000">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-all duration-500 transform sm:hover:scale-110">
                            <div class="absolute bottom-0 bg-black bg-opacity-50 text-white w-full text-center py-2">
                                {{ mb_strtoupper(mb_substr($product->name, 0, 1, encoding: 'UTF-8'), 'UTF-8') . mb_substr($product->name, 1, null, 'UTF-8') }}
                            </div>
                        </div>
                    @elseif ($index === 1 || $index === 2)
                        @if ($index === 1)
                            <div class="flex flex-col gap-4 col-span-1">
                        @endif
                        <div class="relative cursor-pointer aspect-square rounded-2xl overflow-hidden shadow-xl group transition-all duration-300 transform sm:hover:shadow-2xl sm:hover:ring-2 sm:hover:ring-indigo-200 openModal"
                            data-modal-id="productModal{{ $product->id }}" data-aos="fade-up" data-aos-duration="1000"
                            data-aos-delay="{{ $index === 2 ? '400' : '0' }}">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-all duration-500 transform sm:hover:scale-110">
                            <div class="absolute bottom-0 text-[10px] sm:text-sm bg-black bg-opacity-50 text-white w-full text-center py-2">
                                {{ mb_strtoupper(mb_substr($product->name, 0, 1, encoding: 'UTF-8'), 'UTF-8') . mb_substr($product->name, 1, null, 'UTF-8') }}
                            </div>
                        </div>
                        @if ($index === 2)
            </div>
            @endif
            @endif
            @endforeach
        </div>
    </div>


    <!-- Tiêu đề trang -->
    <h1 id="product-title" class="text-4xl font-bold text-center text-blue-500 mt-6 mb-4">Sản phẩm đèn ngủ</h1>
    <div class="flex flex-wrap">
        <!-- Nút mở menu (Hiện ở mobile) -->
        <button id="toggleSidebar"
            class="lg:hidden fixed sm:top-10 top-4 left-4 z-10 mt-14 bg-blue-600/70 text-white p-2 rounded-full shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
        <!-- Backdrop cho danh mục (ẩn mặc định) -->
        <div id="category-backdrop"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden transition-opacity duration-300 lg:hidden">
        </div>

        <!-- Sidebar -->
        <div id="sidebarMenu"
            class="w-3/5 md:w-2/5 lg:w-1/5 p-3 bg-white shadow-xl sm:mr-3 z-40 lg:z-10 overflow-y-auto fixed h-screen left-0 top-0
    lg:sticky lg:top-28 lg:self-start lg:max-h-screen lg:overflow-y-auto
    transition-transform duration-300
    -translate-x-full lg:translate-x-0">


            <h2 class="text-sm md:text-base font-semibold mb-4 text-blue-600 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                Danh Mục
            </h2>


            <ul class="space-y-2">
                <li class="cursor-pointer text-sm font-medium py-2 px-3 bg-blue-100 text-blue-700 sm:hover:bg-blue-200 transition rounded-lg flex items-center justify-between category-filter"
                    data-category="all">
                    <span class="whitespace-normal line-clamp-2">Tất cả sản phẩm</span>
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                @foreach ($categories as $category)
                    <li class="cursor-pointer text-sm font-medium py-2 px-3 bg-gray-100 text-gray-700 sm:hover:bg-gray-200 transition rounded-lg flex items-center justify-between category-filter"
                        data-category="{{ $category->id }}">
                        <span class="whitespace-normal line-clamp">{{ mb_convert_case($category->name, MB_CASE_TITLE, 'UTF-8') }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                @endforeach
            </ul>
            <hr class="my-4 border-gray-300">

            <div class="space-y-2">
                <h3 class="text-sm font-semibold text-blue-600">Lọc theo giá</h3>
                <div class="space-y-1 text-sm text-gray-700">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="price-filter" value="all" checked class="accent-blue-600">
                        <span>Tất cả</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="price-filter" value="0-50000" class="accent-blue-600">
                        <span>0 - 50k</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="price-filter" value="50000-100000" class="accent-blue-600">
                        <span>50k - 100k</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="price-filter" value="100000-250000" class="accent-blue-600">
                        <span>100k - 250k</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="price-filter" value="250000-1000000" class="accent-blue-600">
                        <span>250k trở lên</span>
                    </label>
                </div>
            </div>

        </div>

        <!-- Nội dung sản phẩm -->
        <div class="w-full lg:w-3/4">
            <!-- Container hiển thị tất cả sản phẩm -->
            <div id="all-products-container">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($allProducts as $product)
                        <div class="py-4 z-0">
                            <div
                                class="bg-white shadow-lg rounded-xl overflow-hidden transition-all duration-300 transform sm:hover:scale-105 sm:hover:shadow-2xl sm:hover:ring-2 sm:hover:ring-indigo-200">
                                <div class="product-card"
                                    @if ($product->sale_price > 0) data-price="{{ $product->sale_price }}"
                                                @else
                                                data-price="{{ $product->price }}" @endif>
                                    <x-product-card :product="$product" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Container hiển thị sản phẩm theo danh mục -->
            <div id="products-container" style="display: none;">
                @foreach ($categories as $category)
                    <div class="category-section" data-category="{{ $category->id }}">
                        <div class="mb-8">
                            <div
                                class="relative bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 mb-3 mt-3 text-white font-bold p-3 rounded-lg flex items-center shadow-lg duration-300 ">
                                <h2 class="text-xl tracking-wide capitalize truncate flex-1">{{ $category->name }}
                                </h2>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-aos="fade-up"
                                data-aos-duration="400">
                                @foreach ($category->products as $product)
                                    <div class="py-4">
                                        <div
                                            class="bg-white shadow-lg rounded-xl overflow-hidden transition-all duration-300 transform sm:hover:scale-105 sm:hover:shadow-2xl sm:hover:ring-2 sm:hover:ring-indigo-200">
                                            <div class="product-card"
                                                @if ($product->sale_price > 0) data-price="{{ $product->sale_price }}"
                                                @else
                                                data-price="{{ $product->price }}" @endif>
                                                <x-product-card :product="$product" />
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="no-products-message" class="text-center text-gray-500 font-medium py-5 hidden">
                Không có sản phẩm cần tìm.
            </div>

        </div>
    </div>
    </div>
@endsection

@section('modals')
    @foreach ($categories as $category)
        @foreach ($category->products as $product)
            <div id="productModal{{ $product->id }}"
                class="fixed inset-0 w-screen h-screen hidden z-[9999] flex items-center justify-center bg-black bg-opacity-60 overflow-auto">
                <x-product-modal :product="$product" />
            </div>
        @endforeach
    @endforeach
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleSidebarBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebarMenu');
            const backdrop = document.getElementById('category-backdrop');
            const categoryItems = document.querySelectorAll('.category-filter');

            // Sự kiện mở/đóng sidebar khi click vào nút toggle
            if (toggleSidebarBtn && sidebar && backdrop) {
                toggleSidebarBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    backdrop.classList.toggle('hidden');
                });

                // Đóng sidebar khi click vào backdrop
                backdrop.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                });
            }

            // Lọc theo danh mục
            categoryItems.forEach(item => {
                item.addEventListener('click', () => {
                    const selectedCategory = item.getAttribute('data-category');
                    const productsContainer = document.getElementById('products-container');
                    const allProductsContainer = document.getElementById('all-products-container');

                    if (selectedCategory === 'all') {
                        allProductsContainer.style.display = 'block';
                        productsContainer.style.display = 'none';
                    } else {
                        allProductsContainer.style.display = 'none';
                        productsContainer.style.display = 'block';

                        setTimeout(() => {
                            const title = document.getElementById('product-title');
                            if (title) {
                                const offset = title.getBoundingClientRect().top + window
                                    .pageYOffset - 40;
                                window.scrollTo({
                                    top: offset,
                                    behavior: 'smooth'
                                });
                            }
                        }, 1);
                    }

                    // Tự động đóng sidebar khi chọn danh mục trên mobile
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        backdrop.classList.add('hidden');
                    }
                });
            });
            const priceFilters = document.querySelectorAll('input[name="price-filter"]');

            priceFilters.forEach(filter => {
                filter.addEventListener('change', () => {
                    const value = filter.value;
                    const [min, max] = value === 'all' ? [0, Infinity] : value.split('-').map(
                        Number);

                    // Tìm container đang hiển thị (không bị display: none)
                    const allContainer = document.getElementById('all-products-container');
                    const categoryContainer = document.getElementById('products-container');
                    const visibleContainer = getComputedStyle(allContainer).display !== 'none' ?
                        allContainer : categoryContainer;

                    const productCards = visibleContainer.querySelectorAll('.product-card');

                    let visibleCount = 0;

                    productCards.forEach(card => {
                        const wrapper = card.closest('.py-4');
                        const price = parseInt(card.getAttribute('data-price'));

                        if (value === 'all' || (price >= min && price <= max)) {
                            wrapper.style.display = '';
                            visibleCount++;
                        } else {
                            wrapper.style.display = 'none';
                        }
                    });

                    // Kiểm tra lại số lượng product đang được hiển thị thực sự
                    const actuallyVisible = Array.from(visibleContainer.querySelectorAll(
                            '.product-card'))
                        .filter(card => card.closest('.py-4')?.offsetParent !==
                            null); // offsetParent null = bị ẩn

                    const noProductMessage = document.getElementById('no-products-message');
                    if (noProductMessage) {
                        noProductMessage.style.display = actuallyVisible.length === 0 ? 'block' :
                            'none';
                    }

                    // Cuộn lên tiêu đề nếu có
                    setTimeout(() => {
                        const title = document.getElementById('product-title');
                        if (title) {
                            const offset = title.getBoundingClientRect().top + window
                                .pageYOffset - 40;
                            window.scrollTo({
                                top: offset,
                                behavior: 'smooth'
                            });
                        }
                    }, 1);

                    // Đóng sidebar trên mobile
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        backdrop.classList.add('hidden');
                    }
                });
            });
        });

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

            // Bộ lọc sản phẩm theo danh mục
            const categoryFilters = document.querySelectorAll('.category-filter');
            const categorySections = document.querySelectorAll('.category-section');
            const allProductsContainer = document.getElementById('all-products-container');
            const productsContainer = document.getElementById('products-container');

            categoryFilters.forEach(filter => {
                filter.addEventListener('click', () => {
                    const selectedCategory = filter.getAttribute('data-category');
                    categoryFilters.forEach(item => {
                        item.classList.remove('bg-gray-200');
                    });
                    filter.classList.add('bg-gray-200');

                    if (selectedCategory === 'all') {
                        productsContainer.style.display = 'none';
                        allProductsContainer.style.display = '';
                    } else {
                        allProductsContainer.style.display = 'none';
                        productsContainer.style.display = '';
                        categorySections.forEach(section => {
                            if (section.getAttribute('data-category') ===
                                selectedCategory) {
                                section.style.display = '';
                            } else {
                                section.style.display = 'none';
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
