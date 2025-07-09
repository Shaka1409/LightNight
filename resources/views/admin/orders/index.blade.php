@extends('layout.admin')

@section('content')
    <div class="container px-4 py-5">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold text-dark">Danh sách Đơn Hàng</h1>
            </div>
            <div class="col-auto">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-white border border-end-0 border-gray-300">
                            <i class="fa fa-search text-gray-500"></i>
                        </span>
                        <input type="search" name="q"
                            class="form-control border border-start-0 border-gray-300 bg-white shadow-sm"
                            placeholder="Tìm kiếm đơn hàng..." value="{{ request('q') }}">
                        <button type="submit"
                            class="btn btn-primary shadow-sm">
                            Tìm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bộ lọc theo trạng thái -->
            <form method="GET" action="{{ route('admin.orders.index') }}"
                class="row row-cols-lg-auto g-3 align-items-center mb-4">
                <div class="col">
                    <label for="status_filter" class="form-label mb-0 me-2 fw-medium text-dark">Trạng thái:</label>
                    <select name="status" id="status_filter"
                        class="form-select form-select-sm border-primary rounded-3 shadow-sm"
                        onchange="this.form.submit()">
                        <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>
                            Tất cả</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Đang giao</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
            </form>

        @if (request('q') && $orders->count() === 0)
            <div class="alert alert-danger mb-4" role="alert">
                Không tìm thấy kết quả cho: "{{ request('q') }}"
            </div>
        @elseif (count($orders) > 0)
            @if (request('q') && $orders->count() > 0)
                <p class="text-muted mb-4">Kết quả tìm kiếm cho: "{{ request('q') }}"</p>
            @endif
            <div class="table-responsive rounded-3 shadow-sm">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="py-3 px-4 text-left">Mã đơn hàng</th>
                            <th scope="col" class="py-3 px-4 text-left">Tổng giá</th>
                            <th scope="col" class="py-3 px-4 text-left">Ngày tạo</th>
                            <th scope="col" class="py-3 px-4 text-left">Trạng thái</th>
                            <th scope="col" class="py-3 px-4 text-left">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="py-3 px-4">#{{ $order->id }}</td>
                                <td class="py-3 px-4">{{ number_format($order->total, 0, ',', '.') }} VNĐ</td>
                                <td class="py-3 px-4">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="py-3 px-4">
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                                        class="update-status-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status"
                                            class="form-select"
                                            onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-primary btn-sm shadow-sm">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <p class="text-muted">Hiện chưa có đơn hàng cần xử lý!</p>
        @endif
    </div>
@endsection