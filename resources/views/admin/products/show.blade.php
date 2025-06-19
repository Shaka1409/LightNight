@extends('layout.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4 ">
            {{ mb_strtoupper(mb_substr($product->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product->name, 1, null, 'UTF-8') }}
        </h1>

        <!-- Ảnh sản phẩm -->
        <div class="mb-4">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail"
                    style="width: 120px; height: 120px; object-fit: cover;">
            @else
                <p class="text-muted">Chưa có ảnh</p>
            @endif
        </div>
        <!-- Ảnh phụ -->
        @if ($product->images && $product->images->count())
            <div class="mb-4">
                <h5 class="font-semibold mb-2">Ảnh phụ</h5>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Ảnh phụ" class="img-thumbnail"
                            style="width: 100px; height: 100px; object-fit: cover;">
                    @endforeach
                </div>
            </div>
        @endif


        <!-- Thông tin chi tiết -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Thông tin Sản Phẩm</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Danh mục:</strong>
                        {{ mb_strtoupper(mb_substr($product->category->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product->category->name, 1, null, 'UTF-8') }}
                    </li>
                    <li class="list-group-item">
                        <strong>Tên sản phẩm:</strong>
                        {{ mb_strtoupper(mb_substr($product->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product->name, 1, null, 'UTF-8') }}
                    </li>
                    <li class="list-group-item">
                        <strong>Số lượng tồn kho:</strong>
                        {{ $product->stock_quantity }}
                    </li>
                    <li class="list-group-item">
                        <strong>Số sản phẩm đã bán:</strong>
                        {{ $product->sold_count }}
                    </li>
                    <li class="list-group-item">
                        <strong>Đơn giá:</strong>
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </li>
                    <li class="list-group-item">
                        <strong>Giá sale:</strong>
                        {{ $product->sale_price ? number_format($product->sale_price, 0, ',', '.') . ' VNĐ' : 'Không có' }}
                    </li>
                    <li class="list-group-item">
                        <strong>Màu sắc:</strong>
                        {{ $product->color ? mb_strtoupper(mb_substr($product->color, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product->color, 1, null, 'UTF-8') : 'Không xác định' }}
                    </li>
                    <li class="list-group-item">
                        <strong>Kích thước:</strong>
                        {{ $product->size ? ucfirst($product->size) : 'Không xác định' }}
                    </li>
                    <li class="list-group-item">
                        <strong>Chất liệu:</strong>
                        {{ $product->material ? mb_strtoupper(mb_substr($product->material, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product->material, 1, null, 'UTF-8') : 'Không xác định' }}
                    </li>
                    <li class="list-group-item">
                        <strong>Mô tả:</strong>
                        @if ($product->description)
                            {{ mb_strtoupper(mb_substr($product->description, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product->description, 1, null, 'UTF-8') }}
                        @else
                            <span class="text-muted">Không có mô tả</span>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <strong>Trạng thái:</strong>
                        <span class="{{ $product->status ? 'text-success' : 'text-danger' }}">
                            {{ $product->status ? 'Nổi bật' : 'Không nổi bật' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Nút hành động -->
        <div class="mt-6">
            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary px-4 py-2 me-2">Sửa</a>
            <a href="{{ route('product.index') }}" class="btn btn-secondary px-4 py-2">Quay lại danh sách</a>
        </div>
    </div>
@endsection
