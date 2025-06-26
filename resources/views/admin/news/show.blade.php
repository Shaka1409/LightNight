@extends('layout.admin')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📖 Chi Tiết Tin Tức</h4>
        </div>
        <div class="card-body">
            <!-- Tiêu đề tin tức -->
            <h2 class="fw-bold text-dark">{{ $new->name }}</h2>

            <!-- Ảnh tin tức -->
            <div class="text-center my-4">
                @if ($new->image)
                    <img src="{{ asset('storage/' . $new->image) }}" alt="{{ $new->name }}" 
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
                            <th class="bg-light">Tên tin tức</th>
                            <td>{{ $new->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Link</th>
                            <td>
                                @if ($new->link)
                                    <a href="{{ $new->link }}" target="_blank"
                                       class="text-primary fw-semibold">{{ $new->link }}</a>
                                @else
                                    <span class="text-muted">Không có link</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Mô tả</th>
                            <td>
                                {{ $new->description ? $new->description : 'Không có mô tả' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Trạng thái</th>
                            <td>
                                <span class="badge {{ $new->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $new->status ? 'Nổi bật' : 'Không nổi bật' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Nội dung tin tức -->
            <div class="card my-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">📜 Nội dung tin tức</h5>
                </div>
                <div class="card-body">
                    @if ($new->content)
                        <div class="text-justify">
                            {!! nl2br(e($new->content)) !!}
                        </div>
                    @else
                        <p class="text-muted">Không có nội dung</p>
                    @endif
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('news.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('news.edit', $new->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Sửa tin tức
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
