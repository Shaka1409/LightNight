@extends('layout.admin')
@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header">
      <h1>Tạo Người Dùng</h1>
    </div>
    <div class="card-body">
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('user.store') }}" method="POST" novalidate>
        @csrf
        <!-- Họ và tên -->
        <div class="mb-3">
          <label for="name" class="form-label">Họ và tên</label>
          <input type="text" name="name" id="name" class="form-control" 
                 value="{{ old('name') }}" placeholder="Nhập họ và tên" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" 
                 value="{{ old('email') }}" placeholder="Nhập email" required>
        </div>

        <!-- Số điện thoại -->
        <div class="mb-3">
          <label for="phonenumber" class="form-label">Số điện thoại</label>
          <input type="text" name="phonenumber" id="phonenumber" class="form-control" 
                 value="{{ old('phonenumber') }}" placeholder="Nhập số điện thoại">
        </div>

        <!-- Địa chỉ -->
        <div class="mb-3">
          <label for="address" class="form-label">Địa chỉ</label>
          <input type="text" name="address" id="address" class="form-control" 
                 value="{{ old('address') }}" placeholder="Nhập địa chỉ">
        </div>

        <!-- Vai trò -->
        <div class="mb-3">
          <label for="role" class="form-label">Vai trò</label>
          <select name="role" id="role" class="form-select">
            <option value="user"  {{ old('role') == 'user'  ? 'selected' : '' }}>User</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
          </select>
        </div>

        <!-- Mật khẩu -->
        <div class="mb-3">
          <label for="password" class="form-label">Mật khẩu</label>
          <input type="password" name="password" id="password" class="form-control" 
                 placeholder="Nhập mật khẩu" required>
        </div>

        <!-- Xác nhận mật khẩu -->
        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
          <input type="password" name="password_confirmation" id="password_confirmation" 
                 class="form-control" placeholder="Xác nhận mật khẩu" required>
        </div>

        <button type="submit" class="btn btn-primary">Tạo Người Dùng</button>
      </form>
    </div>
  </div>
</div>
@endsection
