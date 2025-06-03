@extends('layout.admin')
@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header">
      <h1>Sửa Người Dùng</h1>
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

      <form action="{{ route('user.update', $user->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="row">
          <!-- Họ và tên -->
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Họ và tên</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ old('name', $user->name) }}" placeholder="Nhập họ và tên" required>
          </div>
          <!-- Email -->
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" 
                   value="{{ old('email', $user->email) }}" placeholder="Nhập email" required>
          </div>
        </div>
        <div class="row">
          <!-- Số điện thoại -->
          <div class="col-md-6 mb-3">
            <label for="phonenumber" class="form-label">Số điện thoại</label>
            <input type="text" name="phonenumber" id="phonenumber" class="form-control" 
                   value="{{ old('phonenumber', $user->phonenumber) }}" placeholder="Nhập số điện thoại">
          </div>
          <!-- Địa chỉ -->
          <div class="col-md-6 mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" name="address" id="address" class="form-control" 
                   value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ">
          </div>
        </div>
        <div class="row">
          <!-- Vai trò -->
          <div class="col-md-6 mb-3">
            <label for="role" class="form-label">Vai trò</label>
            <select name="role" id="role" class="form-select">
              <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
              <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật Người Dùng</button>
      </form>
    </div>
  </div>
</div>
@endsection
