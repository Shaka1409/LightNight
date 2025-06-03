@extends('layout.app')

@section('title', 'Đăng Ký')

@section('content')
    <div class="min-h-screen   flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md p-8 bg-gradient-to-r from-blue-400/40 to-purple-500/40 rounded-lg shadow-2xl">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Đăng Ký</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-1">
                        {{ __('Tên') }}
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        autocomplete="name" placeholder="Nhập tên của bạn"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none 
                 focus:ring-2 focus:ring-blue-400 @error('name') border-red-500 @enderror">
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-1">
                        {{ __('Email') }}
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email" placeholder="Nhập email của bạn"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none 
                 focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-1">
                        {{ __('Mật khẩu') }}
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        placeholder="Nhập mật khẩu"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none 
                 focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror">
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-1">
                        {{ __('Xác nhận mật khẩu') }}
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Nhập lại mật khẩu"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none 
                 focus:ring-2 focus:ring-blue-400">
                </div>

                <!-- Register Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-md 
                 sm:hover:bg-blue-700 transition-colors duration-200">
                        {{ __('Đăng kí') }}
                    </button>
                </div>
            </form>

            <!-- Link to Login -->
            <div class="mt-4 text-center">
                <p class="text-gray-700">
                    Bạn đã có tài khoản?
                    <a href="{{ route('login') }}" class="text-blue-600 sm:hover:underline font-medium">
                        Đăng nhập ngay
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
