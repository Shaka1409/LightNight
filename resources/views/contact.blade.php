@extends('layout.app')
@section('title', 'Liên hệ')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-serif font-bold text-center text-purple-700">Liên hệ với LightNight</h1>
    <p class="text-center mt-2 text-gray-600">Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7!</p>

    <!-- Thông tin liên hệ -->
    <div class="mt-10 grid md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thông tin liên hệ</h2>
            <ul class="text-gray-700 space-y-2">
                <li><i class="fas fa-map-marker-alt mr-1"></i> 238, Hoàng Quốc Việt, Cầu Giấy, Hà Nội</li>
                <li><i class="fas fa-phone mr-1"></i><a href="tel://0358169047" class="text-blue-600 sm:hover:underline"> +0358169047</a></li>
                <li><i class="fas fa-envelope mr-1"></i><a href="mailto:LightNight@gmail.com" class="text-blue-600 sm:hover:underline"> LightNight@gmail.com</a></li>
                <li><i class="fab fa-facebook"></i>  <a href="https://facebook.com/lightnightvn" class="text-blue-600 sm:hover:underline" target="_blank">facebook.com/lightnightvn</a></li>
            </ul>

            <!-- Google Map -->
            <div class="mt-6">
                <iframe
                    src="https://www.google.com/maps?q=20.85178,105.6036&z=17&output=embed"
                    width="100%" height="250" class="rounded-xl border shadow" 
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <!-- Form liên hệ -->
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Gửi tin nhắn</h2>
            <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-gray-600">Họ và tên</label>
                    <input type="text" name="name" id="name" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label for="email" class="block text-gray-600">Email</label>
                    <input type="email" name="email" id="email" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div>
                    <label for="message" class="block text-gray-600">Nội dung</label>
                    <textarea name="message" id="message" rows="5" required class="w-full border border-gray-300 p-2 rounded"></textarea>
                </div>
                <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded sm:hover:bg-purple-800 transition">
                    Gửi tin nhắn
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
