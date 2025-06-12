@extends('layout.admin')

@section('content')
    <div class="container">
        <h1 class="mb-4">Quản Lý Sản Phẩm</h1>
        <!-- Bộ lọc theo danh mục -->
        <form method="GET" action="{{ route('product.index') }}" class="mb-3">
            <div class="form-group d-inline-block" style="width: auto;">
                <label for="category_filter" class="me-2">Lọc theo danh mục:</label>
                <select name="category" id="category_filter" class="form-control form-control-sm"
                    style="width: auto; display: inline-block;" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ mb_convert_case($category->name, MB_CASE_TITLE, 'UTF-8') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
        @if (count($products) === 0 && request('q'))
            <p class="text-danger mb-2 mt-2">Không tìm thấy kết quả cho: "{{ request('q') }}"</p>
        @elseif (count($products) === 0)
            <p>Chưa có sản phẩm nào. <a class="text-primary" href="{{ route('product.create') }}">Thêm mới sản phẩm?</a></p>
        @else
            <a class="btn btn-primary mb-3" href="{{ route('product.create') }}" role="button">Thêm sản phẩm</a>
            <div class="table-responsive">
                @if (request('q'))
                    <p class="text-muted mb-2 mt-2">Kết quả tìm kiếm cho: "{{ request('q') }}"</p>
                @endif
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Số lượng tồn</th>
                            <th scope="col">Đơn giá</th>
                            <th scope="col">Giá sale</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <th scope="row">{{ $products->firstItem() + $key }}</th>
                                <!-- Hiển thị số thứ tự chính xác -->

                                <!-- Danh mục -->
                                <td>{{ mb_strtoupper(mb_substr($product->category->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product->category->name, 1, null, 'UTF-8') }}
                                    @if ($product->category->status == 0)
                                        <span class="text-danger">(Danh mục ẩn)</span>
                                    @endif
                                </td>

                                <!-- Tên sản phẩm -->
                                <td>{{ mb_strtoupper(mb_substr($product->name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($product->name, 1, null, 'UTF-8') }}
                                </td>

                                <!-- Số lượng tồn kho -->
                                <td>{{ $product->stock_quantity }}</td>

                                <!-- Đơn giá -->
                                <td>{{ number_format($product->price, 0, ',', '.') }}</td>

                                <!-- Giá sale -->
                                <td>{{ $product->sale_price ? number_format($product->sale_price, 0, ',', '.') : 'Không giảm giá' }}
                                </td>

                                <!-- Ảnh -->
                                <td>
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                    @endif
                                </td>

                                <!-- Trạng thái -->
                                <td>
                                    @if ($product->status == 0)
                                        <span class="text-danger">Không nổi bật</span>
                                    @elseif($product->status == 1)
                                        <span class="text-success">Nổi bật</span>
                                    @elseif($product->status == 2)
                                        <span>Ẩn</span>
                                    @endif
                                </td>

                                <!-- Hành động -->
<td style="min-width: 200px;">
    <div class="d-flex flex-wrap gap-1">
        <a href="{{ route('product.show', $product->id) }}" class="btn btn-info btn-sm">Chi tiết</a>
        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning btn-sm">Sửa</a>
        <form action="{{ route('product.destroy', $product->id) }}" method="POST"
            class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
        </form>
    </div>
</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Hiển thị các nút phân trang -->
                <div class="d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
