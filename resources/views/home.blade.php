@extends('layout.app')

@section('content')
    <!-- Slider -->
    <div class="container mx-auto px-4 py-6">
        <div class="relative w-full px-2 sm:px-4 mt-4">
            <!-- Slider Container -->
            <div id="slider"
                class="overflow-hidden relative rounded-2xl shadow-lg h-[220px] sm:h-[280px] md:h-[360px] lg:h-[480px]">
                <!-- Track (slides) -->
                <div id="sliderTrack" class="flex transition-transform duration-700 ease-in-out">
                    @php
                        $images = [$banners[2]->image, $banners[3]->image, $banners[4]->image];
                    @endphp

                    @foreach ($images as $index => $img)
                        <div class="w-full flex-shrink-0 h-full">
                            <img src="{{ asset('storage/' . $img) }}" alt="Slide {{ $index + 1 }}"
                                class="w-full h-full object-cover rounded-2xl">
                        </div>
                    @endforeach

                </div>
            </div>

            <!-- Overlay Content -->
            <div
                class="absolute top-6 left-6 sm:left-10 max-w-[90%] sm:max-w-[50%] bg-black/40 backdrop-blur-md rounded-xl p-4 sm:p-6 text-white space-y-4 shadow-md">
                <p class="text-sm sm:text-lg md:text-xl font-semibold text-cyan-300">
                    Đèn ngủ thông minh với nhiều kiểu dáng và màu sắc khác nhau!
                </p>
                <a href="{{ url('/product') }}"
                    class="inline-block bg-cyan-500 sm:hover:bg-cyan-600 text-white text-xs sm:text-sm md:text-base font-semibold px-4 py-2 rounded-lg transition duration-300">
                    Khám phá ngay
                </a>
            </div>

            <!-- Slider Dots -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-3">
                <button class="slider-dot w-3 h-3 rounded-full bg-gray-300 sm:hover:bg-cyan-400 transition"
                    data-index="0"></button>
                <button class="slider-dot w-3 h-3 rounded-full bg-gray-300 sm:hover:bg-cyan-400 transition"
                    data-index="1"></button>
                <button class="slider-dot w-3 h-3 rounded-full bg-gray-300 sm:hover:bg-cyan-400 transition"
                    data-index="2"></button>
            </div>
        </div>

        <!-- Phần sản phẩm giảm giá -->
        <div class="mx-auto px-4 py-8">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-orange-600 mb-6">Sản Phẩm Giảm Giá</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Sản phẩm giảm giá (Carousel) - Chiếm 2 cột trên desktop -->
                <div class="relative w-full overflow-hidden md:col-span-2">
                    <div id="sale-products-slider" class="flex transition-transform duration-500 ease-in-out w-full">
                        @foreach ($products as $product)
                            @if ($product->sale_price > 0 && $product->sale_price < $product->price)
                                <div
                                    class="product-card bg-white w-full md:w-1/2 lg:w-1/3 px-2 flex-shrink-0 shadow-lg rounded-xl overflow-hidden">
                                    <x-product-card :product="$product" />
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Nút điều hướng slider -->
                    <button id="prev-slide"
                        class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-gray-400/40 text-white p-2 rounded-full sm:hover:bg-gray-700/60">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button id="next-slide"
                        class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-gray-400/40 text-white p-2 rounded-full sm:hover:bg-gray-700/60">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Ảnh quảng cáo - Chiếm 1 cột trên desktop -->
                <div
                    class="relative rounded-xl overflow-hidden shadow-xl order-2 md:order-none w-full h-[300px] md:h-[400px]">
                    <img src="{{ asset('storage/' . $banners[5]->image) }}" alt="Sale Banner" class="w-full h-full object-cover">
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                        <p class="text-white text-xl md:text-2xl font-bold">Ưu đãi đặc biệt!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL CHI TIẾT SẢN PHẨM GIẢM GIÁ -->
        <div class="modals modals-sale">
            @foreach ($products as $product)
                @if ($product->sale_price > 0 && $product->sale_price < $product->price)
                    <div id="productModal{{ $product->id }}"
                        class="fixed inset-0 w-screen h-screen hidden z-50 flex items-center justify-center bg-black bg-opacity-60">
                        <x-product-modal :product="$product" />
                    </div>
                @endif
            @endforeach
        </div>

        <!-- SẢN PHẨM MỚI NHẤT-->
        <div class="mx-auto px-4 py-4">
            <h1 class="text-2xl md:text-3xl font-bold text-center text-blue-500 mb-8">Sản phẩm mới nhất</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up" data-aos-duration="400">
                @foreach ($products->sortByDesc('created_at')->take(8) as $product)
                    <div
                        class="product-card bg-white shadow-lg rounded-xl overflow-hidden transition-transform duration-300 sm:hover:scale-105 sm:hover:shadow-2xl sm:hover:ring-2 sm:hover:ring-indigo-200">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
        <!-- MODAL CHI TIẾT SẢN PHẨM MỚI NHẤT-->
        <div class="modals">
            @foreach ($categories as $category)
                @foreach ($products->sortByDesc('created_at')->take(8) as $product)
                    <div id="productModal{{ $product->id }}"
                        class="fixed inset-0 w-screen h-screen hidden z-50 flex items-center justify-center bg-black bg-opacity-60 overflow-auto">
                        <x-product-modal :product="$product" />
                    </div>
                @endforeach
            @endforeach
        </div>

        <div class="w-full relative max-w-6xl px-6 py-6 mx-auto bg-white rounded-xl shadow-md space-y-10 mt-6">
            <!-- Section 1 -->
            <div class="flex flex-col md:flex-row items-center gap-6" data-aos="fade-right">
                <div class="md:w-1/2">
                    <img class="w-full h-64 object-cover rounded-lg shadow " src="{{ asset('storage/' . $banners[6]->image) }}"
                        alt="Đèn ngủ LightNight">
                </div>
                <div class="md:w-1/2 text-gray-700 text-justify leading-relaxed">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-2">Giới thiệu về LightNight</h2>
                    <p>
                        LightNight chuyên cung cấp các mẫu đèn ngủ thông minh với thiết kế hiện đại, tích hợp nhiều chế độ
                        ánh sáng dịu nhẹ giúp bạn thư giãn và ngủ ngon hơn. Sản phẩm phù hợp cho mọi không gian như phòng
                        ngủ, phòng em bé hay làm quà tặng ý nghĩa.
                    </p>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="flex flex-col md:flex-row-reverse items-center gap-6" data-aos="fade-left">
                <div class="md:w-1/2">
                    <img class="w-full h-64 object-cover rounded-lg shadow" src="{{ asset('storage/' . $banners[7]->image) }}"
                        alt="Đèn ngủ thông minh">
                </div>
                <div class="md:w-1/2 text-gray-700 text-justify leading-relaxed">
                    <h2 class="text-2xl font-bold text-indigo-600 mb-2">Thiết kế tinh tế - Công nghệ hiện đại</h2>
                    <p>
                        Mỗi sản phẩm của LightNight được chăm chút từng chi tiết: từ kiểu dáng nhỏ gọn, màu sắc hài hòa đến
                        chức năng cảm biến và điều khiển từ xa. Chúng tôi mong muốn mang đến không chỉ ánh sáng, mà còn là
                        sự thư thái và tiện nghi cho ngôi nhà của bạn.
                    </p>
                </div>
            </div>

            <img src="{{ asset('image/capybara.png') }}" alt="Capybara"
                class="hidden md:block absolute top-1/2 left-1/2 -translate-x-[50%] -translate-y-[90%] w-32 h-32 object-contain z-20" />

        </div>


        <div class="mx-auto px-4 py-4">
            <h1 class="text-2xl md:text-3xl font-bold text-center text-blue-500 mb-8 mt-8">Sản phẩm nổi bật</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up" data-aos-duration="400">
                @foreach ($products as $product)
                    @if ($product->status == 1)
                        <div
                            class="product-card bg-white shadow-lg rounded-xl overflow-hidden transition-transform duration-300 sm:hover:scale-105 sm:hover:shadow-2xl sm:hover:ring-2 sm:hover:ring-indigo-200">
                            <x-product-card :product="$product" />
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- MODAL CHI TIẾT SẢN PHẨM NỔI BẬT-->
        <div class="modals">
            @foreach ($categories as $category)
                @foreach ($category->products as $product)
                    @if ($product->status == 1)
                        <div id="productModal{{ $product->id }}"
                            class="fixed inset-0 w-screen h-screen hidden z-50 flex items-center justify-center bg-black bg-opacity-60 overflow-auto">
                            <x-product-modal :product="$product" />
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
        <!-- Additional Section -->
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-duration="400">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                Tại sao chọn LightNight?
            </h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-4">
                    <i class="fas fa-lightbulb text-blue-500 text-3xl mb-4"></i>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Thiết kế sáng tạo</h4>
                    <p class="text-gray-600">
                        Mỗi sản phẩm là một tác phẩm nghệ thuật, kết hợp giữa thẩm mỹ và công năng.
                    </p>
                </div>
                <div class="p-4">
                    <i class="fas fa-shield-alt text-blue-500 text-3xl mb-4"></i>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Chất lượng vượt trội</h4>
                    <p class="text-gray-600">
                        Được sản xuất từ vật liệu cao cấp, đảm bảo độ bền và an toàn tuyệt đối.
                    </p>
                </div>
                <div class="p-4">
                    <i class="fas fa-leaf text-blue-500 text-3xl mb-4"></i>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Thân thiện môi trường</h4>
                    <p class="text-gray-600">
                        Công nghệ tiết kiệm năng lượng, góp phần bảo vệ hành tinh xanh.
                    </p>
                </div>
            </div>
        </div>
        <!-- Blog Posts Section -->
        @if (count($blogs) > 0)
            <div class="mx-auto px-4 py-4" data-aos="fade-up" data-aos-duration="400">
                <h2 class="text-xl md:text-2xl font-bold text-center text-blue-500 mb-8 mt-8">Bài viết nổi bật</h2>
                <div class="grid text-center sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($blogs as $blog)
                        <x-blog-card :blog="$blog" />
                    @endforeach
                </div>
            </div>
        @endif

        @if (count($outstandingComments) > 0)
            <div class="mx-auto px-4 py-12 relative" data-aos="fade-up" data-aos-duration="400">
                <h2 class="text-2xl md:text-3xl font-bold text-center text-blue-600 mb-10">Khách hàng nói gì</h2>

                <!-- Nút điều hướng -->
                <button id="prev-slide-cmt"
                    class="absolute top-2/3 left-2 z-10 -translate-y-1/2 bg-gray-600/40 backdrop-blur-md text-white p-2 rounded-full shadow sm:hover:bg-blue-500">
                    <svg class="w-3 h-3 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="next-slide-cmt"
                    class="absolute top-2/3 right-2 z-10 -translate-y-1/2 bg-gray-600/40 backdrop-blur-md text-white p-2 rounded-full shadow sm:hover:bg-blue-500">
                    <svg class="w-3 h-3 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Slider -->
                <div id="comment-slider" class="overflow-hidden">
                    <div class="flex gap-6 px-4 transition-all duration-300 ease-in-out scroll-smooth" id="slider-track">
                        @foreach ($outstandingComments as $comment)
                            <div
                                class="flex-shrink-0 w-[100%] sm:w-[50%] lg:w-[32%] bg-white rounded-2xl shadow-lg p-6 sm:hover:shadow-xl transition">
                                <div class="comment-header flex justify-between items-center border-b pb-3 mb-3">
                                    <span class="font-semibold text-gray-900 text-lg">{{ $comment->user->name }}</span>
                                    <span
                                        class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="comment-body text-gray-700 leading-relaxed text-base italic">
                                    "{{ $comment->comment }}"
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        <!-- Thương hiệu tin cậy -->
        <div class="mb-6 mt-6">
            <h2
                class="text-xl md:text-2xl font-bold font-serif mb-3 uppercase tracking-wide bg-gradient-to-r from-blue-500 to-purple-500 text-transparent bg-clip-text text-center">
                THƯƠNG HIỆU TIN CẬY
            </h2>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://www.amazon.com/s?k=philips+hue+and+bridge"
                    class="w-20 h-20 transform sm:hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('image/philiphue.jpg') }}" alt="Philips Hue"
                        class="w-full h-full object-cover rounded-md shadow-sm">
                </a>
                <a href="https://en.yeelight.com/"
                    class="w-20 h-20 transform sm:hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('image/yeelight.jpg') }}" alt="Yeelight"
                        class="w-full h-full object-cover rounded-md shadow-sm">
                </a>
                <a href="https://www.eglo.com/en/"
                    class="w-20 h-20 transform sm:hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('image/elgo.jpg') }}" alt="Elgo"
                        class="w-full h-full object-cover rounded-md shadow-sm">
                </a>
                <a href="https://www.govee.com/"
                    class="w-20 h-20 transform sm:hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('image/govee.jpg') }}" alt="Govee"
                        class="w-full h-full object-cover rounded-md shadow-sm">
                </a>
                <a href="https://www.marinerluxury.com/en/"
                    class="w-20 h-20 transform sm:hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('image/Mariner.jpg') }}" alt="Mariner"
                        class="w-full h-full object-cover rounded-md shadow-sm">
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // =============================== //
            //          SLIDER COMMENT         //
            // =============================== //
            const commentSliderTrack = document.getElementById("slider-track");
            const commentNextBtn = document.getElementById("next-slide-cmt");
            const commentPrevBtn = document.getElementById("prev-slide-cmt");
            const commentCards = commentSliderTrack?.querySelectorAll("div.flex-shrink-0") || [];
            let commentIndex = 0;

            function getSlidesPerPage() {
                if (window.innerWidth < 640) return 1;
                if (window.innerWidth < 1024) return 2;
                return 3;
            }

            function updateCommentSlider() {
                const slidesPerPage = getSlidesPerPage();
                const cardWidth = commentCards[0]?.offsetWidth + 24 || 0;
                commentSliderTrack.style.transform = `translateX(-${commentIndex * cardWidth}px)`;
            }

            commentNextBtn?.addEventListener("click", () => {
                const slidesPerPage = getSlidesPerPage();
                if (commentIndex < commentCards.length - slidesPerPage) {
                    commentIndex++;
                } else {
                    commentIndex = 0;
                }
                updateCommentSlider();
            });

            commentPrevBtn?.addEventListener("click", () => {
                const slidesPerPage = getSlidesPerPage();
                if (commentIndex > 0) {
                    commentIndex--;
                } else {
                    commentIndex = Math.max(commentCards.length - slidesPerPage, 0);
                }
                updateCommentSlider();
            });

            window.addEventListener("resize", () => {
                const slidesPerPage = getSlidesPerPage();
                if (commentIndex > commentCards.length - slidesPerPage) {
                    commentIndex = Math.max(commentCards.length - slidesPerPage, 0);
                }
                updateCommentSlider();
            });

            updateCommentSlider();


            // ========================== //
            //     SLIDER - HERO BANNER   //
            // ========================== //
            const heroSlider = document.getElementById('slider');
            const heroTrack = document.getElementById('sliderTrack');
            const dots = document.querySelectorAll('.slider-dot');
            const totalSlides = heroTrack?.children.length || 0;
            let currentIndex = 0;
            let autoSlideInterval;

            function showSlide(index) {
                currentIndex = index;
                const width = heroSlider.clientWidth;
                heroTrack.style.transform = `translateX(-${index * width}px)`;
                updateDots();
            }

            function updateDots() {
                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-blue-500', i === currentIndex);
                    dot.classList.toggle('bg-gray-400', i !== currentIndex);
                });
            }

            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                autoSlideInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalSlides;
                    showSlide(currentIndex);
                }, 6000);
            }

            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    showSlide(index);
                    resetAutoSlide();
                });
            });

            window.addEventListener('resize', () => showSlide(currentIndex));
            resetAutoSlide();
            showSlide(currentIndex);


            // ============================= //
            //      SLIDER - SALE PRODUCT    //
            // ============================= //
            const saleSlider = document.getElementById("sale-products-slider");
            const salePrevBtn = document.getElementById("prev-slide");
            const saleNextBtn = document.getElementById("next-slide");

            let saleIndex = 0;

            function updateSaleSlider() {
                const slidesPerPage = getSlidesPerPage();
                const slideWidth = saleSlider.querySelector("div")?.offsetWidth || 0;
                const totalSlides = saleSlider.children.length;
                saleSlider.style.transform = `translateX(-${saleIndex * slideWidth}px)`;
            }

            saleNextBtn?.addEventListener("click", () => {
                const slidesPerPage = getSlidesPerPage();
                const totalSlides = saleSlider.children.length;
                saleIndex = (saleIndex < totalSlides - slidesPerPage) ? saleIndex + 1 : 0;
                updateSaleSlider();
            });

            salePrevBtn?.addEventListener("click", () => {
                const slidesPerPage = getSlidesPerPage();
                const totalSlides = saleSlider.children.length;
                saleIndex = (saleIndex > 0) ? saleIndex - 1 : totalSlides - slidesPerPage;
                updateSaleSlider();
            });

            window.addEventListener("resize", updateSaleSlider);
            updateSaleSlider();

            // ========================= //
            //     AJAX GIỎ HÀNG & MUA   //
            // ========================= //
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

            function handleAddToCart(form) {
                if (!isAuthenticated) {
                    alert("Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!");
                    return;
                }

                const formData = new FormData(form);
                fetch(form.action, {
                        method: "POST",
                        headers: {
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Network response was not ok");
                        return response.json();
                    })
                    .then(data => alert(data.message || "Sản phẩm đã được thêm vào giỏ hàng thành công!"))
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Có lỗi khi thêm sản phẩm vào giỏ hàng.");
                    });
            }

            // Gắn xử lý cho các form thêm vào giỏ hàng và mua ngay
            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleAddToCart(form);
                });
            });

            document.querySelectorAll('.buy-now-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!isAuthenticated) {
                        e.preventDefault();
                        alert("Vui lòng đăng nhập để mua hàng!");
                    }
                });
            });


            // ======================== //
            //      MỞ / ĐÓNG MODAL     //
            // ======================== //
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
                document.querySelectorAll('[id^="productModal"], [id^="saleProductModal"]').forEach(
                    modal => {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
            });
        });

        function updateQuantity(btn, delta, scope) {
            // Tìm input[type="number"] gần nhất với nút bấm (cùng cha .flex hoặc .flex.items-center)
            let input = btn.parentElement.querySelector('input[type="number"]');
            if (!input) {
                // fallback: tìm trong cha lớn hơn
                input = btn.closest('.flex, .flex.items-center').querySelector('input[type="number"]');
            }
            if (!input) return;

            let currentValue = parseInt(input.value) || 1;
            let maxValue = parseInt(input.getAttribute('max')) || Infinity;
            let newValue = currentValue + delta;
            if (newValue > maxValue) newValue = maxValue;
            if (newValue < 1) newValue = 1;
            input.value = newValue;

            // Nếu có input ẩn cho form (cart/checkout), cập nhật luôn:
            const modal = btn.closest('.modals');
            if (modal) {
                modal.querySelectorAll('input[type="hidden"][name="quantity"]').forEach(hiddenInput => {
                    hiddenInput.value = newValue;
                });
            }
        }
    </script>
@endsection
