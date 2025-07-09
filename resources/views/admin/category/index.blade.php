@extends('layout.admin')
@section('content')
    <div class="container mx-auto px-4 py-8 bg-light min-vh-100">
        <div class="card shadow-lg p-4 mb-5 rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-dark">Quản lý Danh Mục</h1>

                <div class="col-auto">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-white border border-end-0 border-gray-300">
                            <i class="fa fa-search text-gray-500"></i>
                        </span>
                        <input type="search" name="q"
                            class="form-control border border-start-0 border-gray-300 bg-white shadow-sm"
                            placeholder="Tìm kiếm danh mục..." value="{{ request('q') }}">
                        <button type="submit"
                            class="btn btn-primary shadow-sm">
                            Tìm
                        </button>
                    </div>
                </form>
            </div>
            </div>

            @if (request('q') && $categories->count() === 0)
                <div class="alert alert-danger rounded-3 mb-4" role="alert">
                    Không tìm thấy kết quả cho: "{{ request('q') }}"
                </div>
            @elseif (count($categories) > 0)
                <a class="btn btn-primary mb-4 rounded-3 fw-semibold shadow-sm w-25"
                    href="{{ route('category.create') }}" role="button">Thêm danh mục</a>
                @if (request('q') && $categories->count() > 0)
                    <div class="card-header bg-light text-muted">
                        Kết quả tìm kiếm cho: "{{ request('q') }}"
                    </div>
                @endif
                <div class="card shadow-lg rounded-3 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="px-4 py-3 fw-semibold">#</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Tên danh mục</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Trạng thái</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Mô tả</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $key => $category)
                                    <tr>
                                        <th scope="row" class="px-4 py-3">{{ $categories->firstItem() + $key }}</th>
                                        <td class="px-4 py-3">
                                            {{ mb_strtoupper(mb_substr($category->name, 0, 1, encoding: 'UTF-8'), 'UTF-8') . mb_substr($category->name, 1, null, 'UTF-8') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($category->status == 1)
                                                <span class="text-success">Hiển thị</span>
                                            @else
                                                <span class="text-danger">Ẩn</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ mb_strtoupper(mb_substr($category->description, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($category->description, 1, null, 'UTF-8') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('category.edit', $category->id) }}"
                                                class="btn btn-warning btn-sm rounded-3 shadow-sm">Sửa</a>
                                            <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa thể loại này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm rounded-3 shadow-sm">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Hiển thị các nút phân trang -->
                    <div class="card-footer bg-light d-flex justify-content-center">
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info rounded-3 mb-4" role="alert">
                    Chưa có thể loại đèn ngủ nào. 
                    <a class="text-primary text-decoration-underline" href="{{ route('category.create') }}">Thêm mới thể loại đèn ngủ?</a>
                </div>
            @endif
        </div>
    </div>
@endsection