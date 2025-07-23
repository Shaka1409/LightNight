@extends('layout.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-gray-700 mb-4">Trang Cá Nhân</h2>



    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Cập nhật thông tin -->
        <div class="bg-gray-100 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Thông tin cá nhân</h3>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block font-medium">Họ tên:</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                           class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Email:</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                           class="w-full p-2 border rounded bg-gray-200 cursor-not-allowed" readonly>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Số điện thoại:</label>
                    <input type="text" name="phonenumber" value="{{ old('phonenumber', auth()->user()->phonenumber) }}" 
                           class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Địa chỉ:</label>
                    <input type="text" name="address" value="{{ old('address', auth()->user()->address) }}" 
                           class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                </div>

                <button type="submit" class="w-full mt-4 bg-blue-600 text-white px-4 py-2 rounded sm:hover:bg-blue-700">
                    Cập nhật thông tin
                </button>
            </form>
        </div>

        <!-- Đổi mật khẩu & Xóa tài khoản -->
        <div class="bg-gray-100 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Bảo mật tài khoản</h3>

            <!-- Đổi mật khẩu -->
            <form action="{{ route('profile.changePassword') }}" method="POST" class="mb-6">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block font-medium">Mật khẩu cũ:</label>
                    <input type="password" name="current_password" class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Mật khẩu mới:</label>
                    <input type="password" name="new_password" class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Xác nhận mật khẩu:</label>
                    <input type="password" name="new_password_confirmation" class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                </div>

                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded sm:hover:bg-green-700">
                    Đổi mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
