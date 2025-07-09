@extends('layout.admin')
@section('content')
    <div class="container mx-auto px-4 py-8 bg-light min-vh-100">
        <div class="card shadow-lg p-4 mb-5 rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-dark">Quản lý Người Dùng</h1>

                 <div class="col-auto">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-white border border-end-0 border-gray-300">
                            <i class="fa fa-search text-gray-500"></i>
                        </span>
                        <input type="search" name="q"
                            class="form-control border border-start-0 border-gray-300 bg-white shadow-sm"
                            placeholder="Tìm kiếm người dùng..." value="{{ request('q') }}">
                        <button type="submit"
                            class="btn btn-primary shadow-sm">
                            Tìm
                        </button>
                    </div>
                </form>
            </div>
            </div>

            <!-- Bộ lọc theo vai trò -->
            <form method="GET" action="{{ route('user.index') }}" class="mb-4">
                <div class="d-flex align-items-center gap-3">
                    <label for="role_filter" class="form-label fw-medium text-dark">Lọc theo vai trò:</label>
                    <select name="role" id="role_filter"
                        class="form-select form-select-sm border-primary rounded-3 shadow-sm"
                        style="width: auto;" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
            </form>

            @if (request('q') && $users->count() === 0)
                <div class="alert alert-danger rounded-3 mb-4" role="alert">
                    Không tìm thấy kết quả cho: "{{ request('q') }}"
                </div>
            @elseif (count($users) > 0)
                <a class="btn btn-primary mb-4 rounded-3 fw-semibold shadow-sm w-25"
                    href="{{ route('user.create') }}" role="button">Thêm người dùng</a>
                <div class="card shadow-lg rounded-3 overflow-hidden">
                    @if (count($users) > 0 && request('q'))
                        <div class="card-header bg-light text-muted">
                            Kết quả tìm kiếm cho: "{{ request('q') }}"
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="px-4 py-3 fw-semibold">#</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Tên</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Vai trò</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Email</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Số điện thoại</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Địa chỉ</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <th scope="row" class="px-4 py-3">{{ $users->firstItem() + $key }}</th>
                                        <td class="px-4 py-3">{{ $user->name }}</td>
                                        <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
                                        <td class="px-4 py-3">{{ $user->email }}</td>
                                        <td class="px-4 py-3">{{ $user->phonenumber }}</td>
                                        <td class="px-4 py-3">
                                            {{ mb_strtoupper(mb_substr($user->address, 0, 1, encoding: 'UTF-8'), 'UTF-8') . mb_substr($user->address, 1, null, 'UTF-8') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('user.edit', $user->id) }}"
                                                class="btn btn-warning btn-sm rounded-3 shadow-sm">Sửa</a>
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xoá người dùng này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm rounded-3 shadow-sm">Xoá</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Hiển thị các nút phân trang -->
                    <div class="card-footer bg-light d-flex justify-content-center">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info rounded-3 mb-4" role="alert">
                    Chưa có người dùng nào!
                </div>
                <a class="btn btn-primary mb-4 rounded-3 fw-semibold shadow-sm"
                    href="{{ route('user.create') }}" role="button">Thêm người dùng</a>
            @endif
        </div>
    </div>
@endsection