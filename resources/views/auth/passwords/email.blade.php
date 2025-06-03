@extends('layout.signup') {{-- hoặc layout dành cho auth nếu bạn có --}}

@section('title', 'Xác nhận Email')
@section('content')
<div class="container mx-auto mt-10">
    <div class="max-w-md mx-auto bg-gradient-to-r from-blue-400/40 to-purple-500/40 p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Quên mật khẩu</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Địa chỉ Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Gửi link đặt lại mật khẩu
                </button>
            </div>
        </form>

        <p class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Quay lại đăng nhập</a>
        </p>
    </div>
</div>
@endsection
