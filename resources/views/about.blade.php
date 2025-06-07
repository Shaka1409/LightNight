@extends('layout.app')

@section('content')
<div class=" py-12" data-aos="fade-up" data-aos-duration="800">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-6 items-center">
            <!-- Video Section -->
            <div class="relative group" data-aos="zoom-in" data-aos-duration="800">
                <video class="w-full h-80 object-cover rounded-lg shadow-lg transform transition duration-500 group-sm:hover:scale-105" autoplay muted loop playsinline>
                    <source src="{{ asset('video/add_lamp.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="text-white text-4xl fas fa-play opacity-75 group-sm:hover:opacity-100 transition"></i>
                </div>
            </div>
            
            <!-- Text Section -->
            <div data-aos="fade-left" data-aos-duration="800" >
                <h2 class="text-3xl font-bold text-gray-800 mb-4">
                    Light 9 - Thắp sáng không gian, nâng tầm phong cách
                </h2>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Light 9 tự hào là thương hiệu chuyên cung cấp các mẫu đèn ngủ hiện đại, tinh tế và chất lượng cao. Với sự kết hợp giữa thiết kế sáng tạo và công nghệ tiên tiến, chúng tôi mang đến những sản phẩm không chỉ giúp chiếu sáng mà còn tạo nên điểm nhấn cho không gian sống.
                </p>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Sản phẩm của Light 9 được kiểm định nghiêm ngặt về chất lượng, đảm bảo độ bền và hiệu suất chiếu sáng vượt trội. Mỗi chiếc đèn đều được chăm chút tỉ mỉ, nhằm mang lại sự hài hòa, thanh lịch và đẳng cấp cho không gian của bạn.
                </p>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Không chỉ là nguồn sáng, Light 9 còn là biểu tượng của sự sáng tạo và tinh tế, giúp biến mỗi góc nhà trở nên ấm cúng và tràn đầy cảm hứng. Hãy cùng chúng tôi khám phá những giải pháp chiếu sáng đột phá, biến không gian sống của bạn thành một tác phẩm nghệ thuật.
                </p>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Tại Light 9, chúng tôi không ngừng đổi mới để mang đến sự đa dạng trong phong cách – từ những mẫu đèn tối giản hiện đại đến các thiết kế sang trọng cổ điển. Dù bạn yêu thích sự tinh gọn hay muốn tạo ấn tượng mạnh mẽ, Light 9 luôn có lựa chọn hoàn hảo dành cho bạn.
                </p>
                <div class="mt-4">
                    <a href="{{ url('/product') }}" class="inline-block bg-blue-500 text-white px-6 py-3 rounded-md sm:hover:bg-blue-600 transition duration-300 font-semibold">
                        Khám phá ngay
                    </a>
                </div>
            </div>            
        </div>

        <!-- Additional Section -->
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
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
<div>fuck</div>
        <!-- Blog Posts Section -->
        @if($blogs->isNotEmpty())
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                Bài viết Mới
            </h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($blogs as $blog)
                   <x-blog-card :blog="$blog"/>
                @endforeach
            </div>
        </div>
        @endif
        <!-- End Blog Posts Section -->
        <div class="mt-12 text-center">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                Liên hệ với chúng tôi
            </h3>
            <p class="text-gray-600 mb-4">
                Nếu bạn có bất kỳ câu hỏi nào hoặc cần thêm thông tin, hãy liên hệ với chúng tôi qua email hoặc điện thoại.
            </p>
            <a href="{{ url('/contact') }}" class="inline-block bg-blue-500 text-white mb-4 px-6 py-3 rounded-md sm:hover:bg-blue-600 transition duration-300 font-semibold">
                Liên hệ ngay </a>
    </div>
</div>
</div>
@endsection
