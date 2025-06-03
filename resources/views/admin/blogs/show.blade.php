@extends('layout.admin')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📖 Chi Tiết Bài Viết</h4>
        </div>
        <div class="card-body">
            <!-- Tiêu đề bài viết -->
            <h2 class="fw-bold text-dark">{{ $blog->name }}</h2>

            <!-- Ảnh bài viết -->
            <div class="text-center my-4">
                @if ($blog->image)
                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->name }}" 
                         class="img-fluid rounded-3 shadow-sm" style="max-width: 300px; height: auto;">
                @else
                    <p class="text-muted">Chưa có ảnh</p>
                @endif
            </div>

            <!-- Thông tin chi tiết -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <tbody>
                        <tr>
                            <th class="bg-light">Tên bài viết</th>
                            <td>{{ $blog->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Link</th>
                            <td>
                                @if ($blog->link)
                                    <a href="{{ $blog->link }}" target="_blank"
                                       class="text-primary fw-semibold">{{ $blog->link }}</a>
                                @else
                                    <span class="text-muted">Không có link</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Mô tả</th>
                            <td>
                                {{ $blog->description ? $blog->description : 'Không có mô tả' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Trạng thái</th>
                            <td>
                                <span class="badge {{ $blog->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $blog->status ? 'Nổi bật' : 'Không nổi bật' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Nội dung bài viết -->
            <div class="card my-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">📜 Nội dung bài viết</h5>
                </div>
                <div class="card-body">
                    @if ($blog->content)
                        <div class="text-justify">
                            {!! nl2br(e($blog->content)) !!}
                        </div>
                    @else
                        <p class="text-muted">Không có nội dung</p>
                    @endif
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('blogs.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Sửa bài viết
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
