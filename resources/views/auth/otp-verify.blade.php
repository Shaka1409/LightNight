@extends('layout.app')

@section('title', 'Xác minh OTP')

@section('content')
<div class="h-screen flex items-center justify-center">
  <div class="w-full max-w-md p-8 bg-gradient-to-r from-blue-400/40 to-purple-500/40 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Nhập mã OTP</h2>
    <form method="POST" action="{{ route('otp.verify') }}">
      @csrf
      <div class="mb-4">
        <label for="otp" class="block text-gray-700 font-medium mb-1">Mã OTP</label>
        <input id="otp" type="text" name="otp" required
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <button type="submit"
        class="w-full bg-blue-500 text-white py-2 rounded-md sm:hover:bg-blue-600 transition-colors">
        Xác minh OTP
      </button>
    </form>
  </div>
</div>
@endsection
