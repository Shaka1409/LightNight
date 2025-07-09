@extends('layout.admin')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4 mr-4">
            <h1 class="mb-4">Quản Lý Sản Phẩm</h1>

            <div class="col-auto">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-white border border-end-0 border-gray-300">
                            <i class="fa fa-search text-gray-500"></i>
                        </span>
                        <input type="search" name="q"
                            class="form-control border border-start-0 border-gray-300 bg-white shadow-sm"
                            placeholder="Tìm kiếm sản phẩm..." value="{{ request('q') }}">
                        <button type="submit"
                            class="btn btn-primary shadow-sm">
                            Tìm
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Bộ lọc theo danh mục và trạng thái -->
        <form method="GET" action="{{ route('product.index') }}" class="row row-cols-lg-auto g-3 align-items-center mb-3">
            <div class="col">
                <label for="category_filter" class="form-label mb-0 me-2">Danh mục:</label>
                <select name="category" id="category_filter" class="form-select form-select-sm border-primary rounded-3 shadow-sm"
                    onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ mb_convert_case($category->name, MB_CASE_TITLE, 'UTF-8') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col">
                <label for="status_filter" class="form-label mb-0 me-2">Trạng thái:</label>
                <select name="status" id="status_filter" class="form-select form-select-sm border-primary rounded-3 shadow-sm" onchange="this.form.submit()">
                    <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>
                        Tất cả</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Nổi bật</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không nổi bật</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
        </form>

        @if (count($products) === 0 && request('q'))
            <p class="text-danger mb-2 mt-2">Không tìm thấy kết quả cho: "{{ request('q') }}"</p>
        @elseif (count($products) === 0)
            <p>Chưa có sản phẩm nào. <a class="text-primary" href="{{ route('product.create') }}">Thêm mới sản phẩm?</a>
            </p>
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
                                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-info btn-sm">Chi
                                            tiết</a>
                                        <a href="{{ route('product.edit', $product->id) }}"
                                            class="btn btn-warning btn-sm">Sửa</a>
                                        <form action="{{ route('product.destroy', $product->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
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
