@extends('layout.app')

@section('title', 'Đăng Nhập')

@section('content')
<div class="h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-12 bg-gradient-to-r from-blue-400/40 to-purple-500/40 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Đăng Nhập</h2>
    <form method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email Address -->
      <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium mb-1">
          {{ __('Email') }}
        </label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror">
      </div>

      <!-- Password -->
      <div class="mb-4">
        <label for="password" class="block text-gray-700 font-medium mb-1">
          {{ __('Mât khẩu') }}
        </label>
        <input id="password" type="password" name="password" required autocomplete="current-password"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror">
      </div>

      <!-- Remember Me -->
      <div class="mb-4 flex items-center">
        <input id="remember" type="checkbox" name="remember" class="mr-2" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember" class="text-gray-700 font-medium">
          {{ __('Ghi nhớ mật khẩu') }}
        </label>
      </div>

      <!-- Login Button -->
      <div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md sm:hover:bg-blue-600 transition-colors">
          {{ __('Đăng nhập') }}
        </button>
      </div>

      <!-- Forgot Password Link -->
      <div class="mt-4 text-center">
          <a href="{{ route('otp.request') }}" class="text-blue-500 sm:hover:underline font-medium">
            {{ __('Quên mật khẩu?') }}
          </a>
      </div>
    </form>
    <!-- Link to Registration -->
    <div class="mt-6 text-center">
        <p class="text-gray-700">
          Bạn chưa có tài khoản?
          <a href="{{ route('register') }}" class="text-blue-500 sm:hover:underline font-medium">
            Đăng ký ngay
          </a>
        </p>
      </div>
  </div>
</div>
@endsection
