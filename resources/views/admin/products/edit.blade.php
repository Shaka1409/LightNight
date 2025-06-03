@extends('layout.admin')
@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1>Chỉnh Sửa Sản Phẩm</h1>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM CHÍNH -->
                <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <!-- Danh mục, tên, số lượng -->
                        <div class="col-md-4">
                            <label for="category_id" class="form-label">Danh mục</label>
                            <select name="category_id" class="form-select" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="stock_quantity" class="form-label">Số lượng tồn kho</label>
                            <input type="number" class="form-control" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" min="0">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Giá, giá sale, màu -->
                        <div class="col-md-4">
                            <label for="price" class="form-label">Giá</label>
                            <input type="number" step="1000" min="0" name="price" class="form-control" value="{{ $product->price }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="sale_price" class="form-label">Giá sale</label>
                            <input type="number" step="1000" min="0" name="sale_price" class="form-control" value="{{ $product->sale_price }}">
                        </div>
                        <div class="col-md-4">
                            <label for="color" class="form-label">Màu sắc</label>
                            <input type="text" name="color" class="form-control" value="{{ $product->color }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Kích thước, chất liệu, trạng thái -->
                        <div class="col-md-4">
                            <label for="size" class="form-label">Kích thước</label>
                            <input type="text" name="size" class="form-control" value="{{ $product->size }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="material" class="form-label">Chất liệu</label>
                            <input type="text" name="material" class="form-control" value="{{ $product->material }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Không nổi bật</option>
                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Nổi bật</option>
                                <option value="2" {{ $product->status == 2 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description" rows="4" class="form-control">{{ $product->description }}</textarea>
                    </div>

                    <!-- Ảnh đại diện -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Ảnh đại diện</label>
                        <input type="file" name="image" class="form-control">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="" class="mt-2" style="width:100px;">
                        @endif
                    </div>

                    <!-- Thêm ảnh phụ -->
                    <div class="mb-3">
                        <label for="sub_images" class="form-label">Ảnh phụ</label>
                        <input type="file" name="sub_images[]" multiple class="form-control"
                            onchange="if(this.files.length > 4){ alert('Chỉ chọn tối đa 4 ảnh!'); this.value=''; }">
                    </div>

                    <!-- ✅ Nút submit đặt cuối -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                    </div>
                </form>

                <!-- ẢNH PHỤ HIỆN CÓ (ngoài form chính) -->
                @if ($product->images && $product->images->count())
                    <div class="mt-4">
                        <label class="form-label">Ảnh phụ hiện tại</label>
                        <div class="row">
                            @foreach ($product->images as $image)
                                <div class="col-md-3 mb-3 text-center">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail mb-2" style="width:100px; height:100px; object-fit:cover;">
                                    <form action="{{ route('product.deleteSubImage', $image->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xoá ảnh này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Xoá</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
