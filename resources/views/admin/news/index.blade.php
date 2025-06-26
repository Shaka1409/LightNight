@extends('layout.admin')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 mr-4">
            <h1 class="mb-4">Quản lý Tin Tức</h1>

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

        @if (request('q') && $news->count() === 0)
            <p class="text-danger mb-2 mt-2">Không tìm thấy kết quả cho: "{{ request('q') }}"</p>
        @elseif ($news->count() > 0)
            <a class="btn btn-primary mb-3" href="{{ route('news.create') }}" role="button">Thêm tin tức mới</a>
            @if (request('q') && $news->count() > 0)
                <p class="text-muted mb-2 mt-2">Kết quả tìm kiếm cho: "{{ request('q') }}"</p>
            @endif
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên tin tức</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($news as $key => $new)
                            <tr>
                                <!-- Hiển thị số thứ tự chính xác -->
                                <th scope="row">{{ $news->firstItem() + $key }}</th>

                                <!-- Tên new (chữ cái đầu in hoa) -->
                                <td>
                                    {{ mb_strtoupper(mb_substr($new->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($new->name, 1, null, 'UTF-8') }}
                                </td>

                                <!-- Ảnh new -->
                                <td>
                                    @if ($new->image)
                                        <img src="{{ asset('storage/' . $new->image) }}" alt="{{ $new->name }}"
                                            class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                    @endif
                                </td>

                                <!-- Trạng thái new -->
                                <td>
                                    @if ($new->status == 1)
                                        <span class="text-success">Nổi bật</span>
                                    @else
                                        <span class="text-danger">Không nổi bật</span>
                                    @endif
                                </td>

                                <!-- Hành động (Chi tiết / Sửa / Xóa) -->
                                <td>
                                    <a href="{{ route('news.show', $new->id) }}" class="btn btn-info btn-sm">Chi tiết</a>
                                    <a href="{{ route('news.edit', $new->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                    <form action="{{ route('news.destroy', $new->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa new này?');">
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
                    {{ $news->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <p>
                Chưa có new nào.
                <a class="text-primary" href="{{ route('news.create') }}">Thêm mới tin tức?</a>
            </p>
        @endif
    </div>
@endsection
