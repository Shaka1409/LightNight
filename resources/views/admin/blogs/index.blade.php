@extends('layout.admin')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Quản lý Bài Viết</h1>

        @if (request('q') && $blogs->count() === 0)
            <p class="text-danger mb-2 mt-2">Không tìm thấy kết quả cho: "{{ request('q') }}"</p>
        @elseif ($blogs->count() > 0)
            <a class="btn btn-primary mb-3" href="{{ route('blogs.create') }}" role="button">Thêm bài viết mới</a>
            @if (request('q') && $blogs->count() > 0)
                <p class="text-muted mb-2 mt-2">Kết quả tìm kiếm cho: "{{ request('q') }}"</p>
            @endif
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên bài viết</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $key => $blog)
                            <tr>
                                <!-- Hiển thị số thứ tự chính xác -->
                                <th scope="row">{{ $blogs->firstItem() + $key }}</th>

                                <!-- Tên Blog (chữ cái đầu in hoa) -->
                                <td>
                                    {{ mb_strtoupper(mb_substr($blog->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($blog->name, 1, null, 'UTF-8') }}
                                </td>

                                <!-- Ảnh Blog -->
                                <td>
                                    @if ($blog->image)
                                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->name }}"
                                            class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                    @endif
                                </td>

                                <!-- Trạng thái Blog -->
                                <td>
                                    @if ($blog->status == 1)
                                        <span class="text-success">Nổi bật</span>
                                    @else
                                        <span class="text-danger">Không nổi bật</span>
                                    @endif
                                </td>

                                <!-- Hành động (Chi tiết / Sửa / Xóa) -->
                                <td>
                                    <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-info btn-sm">Chi tiết</a>
                                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa blog này?');">
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
                    {{ $blogs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <p>
                Chưa có Blog nào.
                <a class="text-primary" href="{{ route('blogs.create') }}">Thêm mới bài viết?</a>
            </p>
        @endif
    </div>
@endsection
