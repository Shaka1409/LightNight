@extends('layout.signup')

@section('title', 'Xác nhận')
@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-gradient-to-r from-blue-400/40 to-purple-500/40 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-700 text-center">
            Xác nhận mật khẩu
        </h2>
        <p class="text-sm text-gray-600 text-center mb-4">
            Vui lòng nhập lại mật khẩu trước khi tiếp tục.
        </p>
        
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                <input id="password" type="password" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    name="password" required autocomplete="current-password">

                @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" 
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                    Xác nhận
                </button>
            </div>

            <div class="text-center mt-4">
                @if (route('password.request'))
                <a href="{{ route('password.request') }}" class="text-indigo-500 text-sm hover:underline">
                    Quên mật khẩu?
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

@endsection
