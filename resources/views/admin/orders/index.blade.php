@extends('layout.admin')

@section('content')
    <div class="container mx-auto px-4 py-4">
        <h1 class="text-3xl font-bold mb-6">Danh sách Đơn Hàng</h1>
        @if (request('q') && $orders->count() === 0)
            <p class="text-danger mb-2 mt-2">Không tìm thấy kết quả cho: "{{ request('q') }}"</p>
        @elseif (count($orders) > 0)
            @if (request('q') && $orders->count() > 0)
                <p class="text-muted mb-2 mt-2">Kết quả tìm kiếm cho: "{{ request('q') }}"</p>
            @endif
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 table table-striped">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Mã đơn hàng</th>
                            <th class="py-3 px-4 text-left">Tổng giá</th>
                            <th class="py-3 px-4 text-left">Ngày tạo</th>
                            <th class="py-3 px-4 text-left">Trạng thái</th>
                            <th class="py-3 px-4 text-left">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="py-3 px-4">{{ $order->id }}</td>
                                <td class="py-3 px-4">{{ number_format($order->total, 0, ',', '.') }} VNĐ</td>
                                <td class="py-3 px-4">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="py-3 px-4">
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                                        class="update-status-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm"
                                            onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ
                                                xử lý</option>
                                            <option value="processing"
                                                {{ $order->status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>
                                                Đang giao</option>
                                            <option value="delivered"
                                                {{ $order->status === 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                                            <option value="cancelled"
                                                {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-info btn-sm hover:text-white">Xem chi tiết</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Hiển thị các nút phân trang -->
                <div class="d-flex justify-content-center">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <p>Hiện chưa có đơn hàng cần xử lý!</p>
        @endif
    </div>
@endsection
