@extends('layout.admin')
@section('content')
    <div class="container mx-auto px-4 py-8 bg-light min-vh-100">
        <div class="card shadow-lg p-4 mb-5 rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-dark">Quản lý Bình Luận</h1>

                <div class="col-auto">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-white border border-end-0 border-gray-300">
                            <i class="fa fa-search text-gray-500"></i>
                        </span>
                        <input type="search" name="q"
                            class="form-control border border-start-0 border-gray-300 bg-white shadow-sm"
                            placeholder="Tìm kiếm bình luận..." value="{{ request('q') }}">
                        <button type="submit"
                            class="btn btn-primary shadow-sm">
                            Tìm
                        </button>
                    </div>
                </form>
            </div>
            </div>

            <form method="GET" action="{{ route('admin.comments.index') }}"
                class="row row-cols-lg-auto g-3 align-items-center mb-4">
                <div class="col">
                    <label for="status_filter" class="form-label mb-0 me-2 fw-medium text-dark">Trạng thái:</label>
                    <select name="status" id="status_filter" class="form-select form-select-sm border-primary rounded-3 shadow-sm" onchange="this.form.submit()">
                        <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>
                            Tất cả</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Chờ kiểm duyệt</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Nổi bật</option>
                    </select>
                </div>
            </form>

            @if (request('q') && $comments->count() === 0)
                <div class="alert alert-danger rounded-3 mb-4" role="alert">
                    Không tìm thấy kết quả cho: "{{ request('q') }}"
                </div>
            @elseif (count($comments) > 0)
                @if ($comments->count() > 0 && request('q'))
                    <div class="card-header bg-light text-muted">
                        Kết quả tìm kiếm cho: "{{ request('q') }}"
                    </div>
                @endif
                <div class="card shadow-lg rounded-3 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Tên Người Dùng</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Tên Sản Phẩm</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Nội Dung</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Thời Gian</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Trạng Thái</th>
                                    <th scope="col" class="px-4 py-3 fw-semibold">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comments as $comment)
                                    <tr>
                                        <td class="px-4 py-3">{{ ucfirst($comment->user->name) }}</td>
                                        <td class="px-4 py-3">
                                            {{ mb_strtoupper(mb_substr($comment->product->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($comment->product->name, 1, null, 'UTF-8') }}
                                        </td>
                                        <td class="px-4 py-3">{{ $comment->comment }}</td>
                                        <td class="px-4 py-3">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <form action="{{ route('admin.comments.updateStatus', $comment->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-select form-select-sm border-warning rounded-3 shadow-sm"
                                                    onchange="this.form.submit()">
                                                    <option value="0" {{ $comment->status == 0 ? 'selected' : '' }}>Ẩn
                                                    </option>
                                                    <option value="1" {{ $comment->status == 1 ? 'selected' : '' }}>Chờ kiểm duyệt
                                                    </option>
                                                    <option value="2" {{ $comment->status == 2 ? 'selected' : '' }}>Hiển thị
                                                    </option>
                                                    <option value="3" {{ $comment->status == 3 ? 'selected' : '' }}>Nổi bật
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3">
                                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-3 shadow-sm">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Hiển thị các nút phân trang -->
                    <div class="card-footer bg-light d-flex justify-content-center">
                        {{ $comments->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info rounded-3 mb-4" role="alert">
                    Hiện chưa có bình luận nào!
                </div>
            @endif
        </div>
    </div>
@endsection