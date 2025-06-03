@extends('layout.admin')
@section('content')
    <div class="container">
        <div class="card">
            <h1 class="card-header">Thêm Sản Phẩm</h1>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="row g-3" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data"
                    novalidate>
                    @csrf
                    <!-- Chọn danh mục -->
                    <div class="col-md-4">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tên sản phẩm -->
                    <div class="col-md-4">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Tên sản phẩm"
                            required>
                    </div>

                    <!-- Số lượng tồn kho -->
                    <div class="col-md-4">
                        <label for="stock_quantity" class="form-label">Số lượng tồn kho</label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0">
                    </div>

                    <!-- Giá -->
                    <div class="col-md-4">
                        <label for="price" class="form-label">Giá</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Giá"
                            step="1000" min="0" required>
                    </div>

                    <!-- Giá sale -->
                    <div class="col-md-4">
                        <label for="sale_price" class="form-label">Giá sale</label>
                        <input type="number" class="form-control" id="sale_price" name="sale_price" placeholder="Giá sale"
                            step="1000" min="0">
                    </div>

                    <!-- Màu sắc -->
                    <div class="col-md-4">
                        <label for="color" class="form-label">Màu sắc</label>
                        <input type="text" class="form-control" id="color" name="color" placeholder="Màu sắc"
                            required>
                    </div>

                    <!-- Kích thước -->
                    <div class="col-md-4">
                        <label for="size" class="form-label">Kích thước</label>
                        <input type="text" class="form-control" id="size" name="size" placeholder="Kích thước"
                            required>
                    </div>

                    <!-- Chất liệu -->
                    <div class="col-md-4">
                        <label for="material" class="form-label">Chất liệu</label>
                        <input type="text" class="form-control" id="material" name="material" placeholder="Chất liệu"
                            required>
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="col-md-4">
                        <label for="image" class="form-label">Ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                    </div>
                    <!-- Ảnh phụ -->
                    <div class="col-md-4">
                        <label for="sub_images" class="form-label">Ảnh phụ</label>
                        <input type="file" class="form-control" id="sub_images" name="sub_images[]" multiple
                            onchange="if(this.files.length > 4){ alert('Chỉ chọn tối đa 4 ảnh!'); this.value=''; }">
                        <small class="form-text text-muted">Bạn có thể chọn nhiều ảnh phụ.</small>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-md-4">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="0">Không nổi bật</option>
                            <option value="1">Nổi bật</option>
                            <option value="2">Ẩn</option>
                        </select>
                    </div>

                    <!-- Mô tả -->
                    <div class="col-12">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Mô tả sản phẩm"></textarea>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
