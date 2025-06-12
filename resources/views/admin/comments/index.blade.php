@extends('layout.admin')
@section('content')
    <div class="container">
        <h1 class="mb-4">Quản lý Bình Luận</h1>

        @if (request('q') && $comments->count() === 0)
            <p class="text-danger mb-2 mt-2">Không tìm thấy kết quả cho: "{{ request('q') }}"</p>
        @elseif (count($comments) > 0)
        @if($comments->count() > 0 && request('q'))
            <p class="text-muted mb-2 mt-2">Kết quả tìm kiếm cho: "{{ request('q') }}"</p>
        @endif
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tên Người Dùng</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Nội Dung</th>
                        <th>Thời Gian</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comments as $comment)
                        <tr>
                            <td class="px-4 py-2">{{ ucfirst($comment->user->name) }}</td>
                            <td>{{ mb_strtoupper(mb_substr($comment->product->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($comment->product->name, 1, null, 'UTF-8') }}
                            </td>
                            <td class="px-4 py-2">{{ $comment->comment }}</td>
                            <td class="px-4 py-2">{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('admin.comments.updateStatus', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="0" {{ $comment->status == 0 ? 'selected' : '' }}>Ẩn</option>
                                        <option value="1" {{ $comment->status == 1 ? 'selected' : '' }}>Chờ kiểm
                                            duyệt</option>
                                        <option value="2" {{ $comment->status == 2 ? 'selected' : '' }}>Hiển thị
                                        </option>
                                        <option value="3" {{ $comment->status == 3 ? 'selected' : '' }}>Nổi bật
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-2">
                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">
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
                {{ $comments->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @else
            <p>Hiện chưa có bình luận nào!</p>
        @endif
    </div>
@endsection
