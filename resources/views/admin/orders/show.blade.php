@extends('layout.admin')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Chi tiết đơn hàng #{{ $order->id }}</h5>
                </div>
                <div class="card-body">
                    <p><strong><i class="fas fa-user"></i> Người đặt:</strong> {{ $order->user->name }}</p>
                    <p><strong><i class="fas fa-map-marker-alt"></i> Địa chỉ:</strong> {{ $order->address }}</p>
                    <p><strong><i class="fas fa-phone"></i> Số điện thoại:</strong> {{ $order->phone }}</p>
                    <p><strong><i class="fas fa-coins"></i> Tổng tiền:</strong> 
                        <span class="text-danger fw-bold">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                    </p>
                    <p><strong><i class="fas fa-info-circle"></i> Trạng thái:</strong> 
                        <span class="badge 
                            {{ $order->status == 'pending' ? 'bg-warning' : 
                               ($order->status == 'processing' ? 'bg-primary' : 
                               ($order->status == 'shipped' ? 'bg-info' : 
                               ($order->status == 'delivered' ? 'bg-success' : 'bg-danger'))) }}">
                            {{ $order->status == 'pending' ? 'Chờ xử lý' : 
                               ($order->status == 'processing' ? 'Đang xử lý' : 
                               ($order->status == 'shipped' ? 'Đang giao' : 
                               ($order->status == 'delivered' ? 'Đã giao' : 'Đã hủy'))) }}
                        </span>
                    </p>                    
                    <p><strong><i class="fas fa-calendar-alt"></i> Ngày đặt:</strong> 
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Chi tiết sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Giá</th>
                                    <th class="text-end">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                     alt="{{ $detail->product->name }}" 
                                                     class="rounded img-thumbnail me-2" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                                {{ $detail->product->name }}
                                            </div>                                            
                                        </td>
                                        <td class="text-center">{{ $detail->quantity }}</td>
                                        <td class="text-end">{{ number_format($detail->price, 0, ',', '.') }} VNĐ</td>
                                        <td class="text-end fw-bold">{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
