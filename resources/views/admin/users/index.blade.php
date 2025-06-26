@extends('layout.admin')
@section('content')
    <div class="container">
        
         <div class="d-flex justify-content-between align-items-center mb-4 mr-4">
            <h1 class="mb-4">Quản lý Người Dùng</h1>

            <form action="{{ url()->current() }}" method="GET" class="mb-3">
                <div class="input-group input-group-sm">
                    <input type="search" name="q"
                        class="form-control border border-warning rounded-start-pill bg-white shadow-sm"
                        placeholder="Tìm kiếm..." value="{{ request('q') }}">
                    <button type="submit" class="btn rounded-end-pill text-white fw-bold px-3"
                        style="background-color: #fd7e14; box-shadow: 0 4px 12px rgba(253, 126, 20, 0.5);">
                        <i class="fa fa-search me-1"></i> Tìm
                    </button>
                </div> 
            </form>

        </div>

        <!-- Bộ lọc theo vai trò -->
        <form method="GET" action="{{ route('user.index') }}" class="mb-3 d-inline-block" style="width: auto;">
            <div class="form-group" style="width: auto;">
                <label for="role_filter" class="mr-2">Lọc theo vai trò:</label>
                <select name="role" id="role_filter" class="form-control form-control-sm"
                    style="width: auto; display: inline-block;" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
        </form>
        <br>

        @if (request('q') && $users->count() === 0)
            <p class="text-danger mb-2 mt-2">Không tìm thấy kết quả cho: "{{ request('q') }}"</p>
        @elseif (count($users) > 0)
            <a class="btn btn-primary mb-3" href="{{ route('user.create') }}" role="button">Thêm người dùng</a>
            <div class="table-responsive">
                @if (count($users) > 0 && request('q'))
                    <p class="text-muted mb-2 mt-2">Kết quả tìm kiếm cho: "{{ request('q') }}"</p>
                @endif
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên</th>
                            <th scope="col">Vai trò</th>
                            <th scope="col">Email</th>
                            <th scope="col">Số điện thoại</th>
                            <th scope="col">Địa chỉ</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <th scope="row">{{ $users->firstItem() + $key }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phonenumber }}</td>
                                <td>{{ mb_strtoupper(mb_substr($user->address, 0, 1, encoding: 'UTF-8'), 'UTF-8') . mb_substr($user->address, 1, null, 'UTF-8') }}
                                </td>
                                <td>
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xoá người dùng này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xoá</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Hiển thị các nút phân trang -->
                <div class="d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <p>Chưa có người dùng nào!</p>
            <a class="btn btn-primary mb-3" href="{{ route('user.create') }}" role="button">Thêm người dùng</a>
        @endif
    </div>
@endsection
