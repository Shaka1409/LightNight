@extends('layout.app')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-8 bg-gradient-to-r from-blue-400/40 to-purple-500/40 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Đặt lại mật khẩu</h2>

  
    <form method="POST" action="{{ route('otp.reset') }}">
      @csrf

      <input type="hidden" name="email" value="{{ $email }}">

      <div class="mb-4">
        <label for="password" class="block text-gray-700 font-medium mb-1">Mật khẩu mới</label>
        <input id="password" type="password" name="password" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <div class="mb-4">
        <label for="password_confirmation" class="block text-gray-700 font-medium mb-1">Xác nhận mật khẩu</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <button type="submit"
        class="w-full bg-blue-500 text-white py-2 rounded-md sm:hover:bg-blue-600 transition-colors">
        Đặt lại mật khẩu
      </button>
    </form>
  </div>
</div>
@endsection
