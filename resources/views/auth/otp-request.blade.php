@extends('layout.app')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="h-screen flex items-center justify-center">
  <div class="w-full max-w-3xl p-8 bg-gradient-to-r from-blue-400/40 to-purple-500/40 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Quên mật khẩu</h2>
    
    <form method="POST" action="{{ route('otp.send') }}">
      @csrf
      <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
        <input id="email" type="email" name="email" required autofocus
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <button type="submit"
        class="w-full bg-blue-500 text-white py-2 rounded-md sm:hover:bg-blue-600 transition-colors">
        Gửi mã OTP
      </button>
    </form>

    <div class="mt-4 text-center">
      <a href="{{ route('login') }}" class="text-blue-500 sm:hover:underline font-medium">Quay lại đăng nhập</a>
    </div>
  </div>
</div>
@endsection
