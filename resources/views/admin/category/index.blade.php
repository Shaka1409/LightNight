@extends('layout.admin')
@section('content')
    <div class="container">
        
         <div class="d-flex justify-content-between align-items-center mb-4 mr-4">
            <h1 class="mb-4">Quản lý Danh Mục</h1>

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
        @if (request('q') && $categories->count() === 0)
            <p class="text-danger mb-2 mt-2">Không tìm thấy kết quả cho: "{{ request('q') }}"</p>
        @elseif (count($categories) > 0)
            <a class="btn btn-primary mb-3" href="{{ route('category.create') }}" role="button">Thêm danh mục</a>
            @if (request('q') && $categories->count() > 0)
                <p class="text-muted mb-2 mt-2">Kết quả tìm kiếm cho: "{{ request('q') }}"</p>
            @endif
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên danh mục</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Mô tả</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                            <tr>
                                <th scope="row">{{ $categories->firstItem() + $key }}</th>
                                <td>
                                    {{ mb_strtoupper(mb_substr($category->name, 0, 1, encoding: 'UTF-8'), 'UTF-8') . mb_substr($category->name, 1, null, 'UTF-8') }}
                                </td>
                                <td>
                                    @if ($category->status == 1)
                                        <span class="text-success">Hiển thị</span>
                                    @else
                                        <span class="text-danger">Ẩn</span>
                                    @endif
                                </td>
                                <td>
                                    {{ mb_strtoupper(mb_substr($category->description, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($category->description, 1, null, 'UTF-8') }}
                                </td>
                                <td>
                                    <a href="{{ route('category.edit', $category->id) }}"
                                        class="btn btn-warning btn-sm">Sửa</a>
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa thể loại này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Hiển thị các nút phân trang -->
                <div class="d-flex justify-content-center">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <p>Chưa có thể loại đèn ngủ nào. <a class="text-primary" href="{{ route('category.create') }}">Thêm mới thể loại đèn ngủ?</a></p>
        @endif
    </div>
@endsection
