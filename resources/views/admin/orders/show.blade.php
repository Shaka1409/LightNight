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
                        <p><strong><i class="fas fa-user"></i> Người nhận:</strong> {{ $order->name }}</p>
                        <p><strong><i class="fas fa-map-marker-alt"></i> Địa chỉ:</strong> {{ $order->address }}</p>
                        <p><strong><i class="fas fa-phone"></i> Số điện thoại:</strong> {{ $order->phone }}</p>
                        <p><strong><i class="fas fa-shipping-fast"></i> Khu vực giao hàng:</strong>
                            {{ $order->shipping_area == 'hanoi' ? 'Nội thành Hà Nội' : ($order->shipping_area == 'mienbac' ? 'Các tỉnh miền Bắc' : 'Toàn quốc') }}
                        </p>
                        <p><strong><i class="fas fa-truck"></i> Phí vận chuyển:</strong>
                            {{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ
                        </p>
                        <p><strong><i class="fas fa-money-bill-wave"></i> Thanh toán:</strong>
                            {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng' }}
                        </p>
                        <!-- Ảnh thumbnail + trigger -->

                        @if ($order->payment_method == 'bank' && !$order->is_paid)
                            @foreach ($order->payment_proof as $index => $proof)
                                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $index }}">
                                    <img src="{{ asset('storage/' . $proof) }}" class="img-thumbnail me-2 mb-2"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </a>

                                <!-- Modal hiển thị ảnh lớn -->
                                <div class="modal fade" id="imageModal{{ $index }}" tabindex="-1"
                                    aria-labelledby="imageModalLabel{{ $index }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <img src="{{ asset('storage/' . $proof) }}" class="w-100"
                                                    alt="Ảnh chuyển khoản">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <form action="{{ route('admin.orders.confirmPayment', $order->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc đơn hàng đã được thanh toán?')" class="mt-3">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">✅ Xác nhận đã thanh toán</button>
                            </form>
                        @elseif ($order->is_paid)
                            <p class="text-success fw-bold mt-3">✔ Đã xác nhận thanh toán</p>
                        @endif
                        <p><strong><i class="fas fa-coins"></i> Tổng tiền:</strong>
                            <span class="text-danger fw-bold">{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
                        </p>
                        <p><strong><i class="fas fa-info-circle"></i> Trạng thái:</strong>
                            <span
                                class="badge 
                            {{ $order->status == 'pending'
                                ? 'bg-warning'
                                : ($order->status == 'processing'
                                    ? 'bg-primary'
                                    : ($order->status == 'shipped'
                                        ? 'bg-info'
                                        : ($order->status == 'delivered'
                                            ? 'bg-success'
                                            : 'bg-danger'))) }}">
                                {{ $order->status == 'pending'
                                    ? 'Chờ xử lý'
                                    : ($order->status == 'processing'
                                        ? 'Đang xử lý'
                                        : ($order->status == 'shipped'
                                            ? 'Đang giao'
                                            : ($order->status == 'delivered'
                                                ? 'Đã giao'
                                                : 'Đã hủy'))) }}
                            </span>
                        </p>
                        <p><strong><i class="fas fa-calendar-alt"></i> Ngày đặt:</strong>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </p>
                        <form action="{{ route('admin.orders.updateShipper', $order->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label class="form-label">Tên shipper</label>
                                <input type="text" name="shipper_name" value="{{ $order->shipper_name }}"
                                    class="form-control bg-light" required
                                    @if ($order->status != 'shipped' && $order->status != 'processing') readonly @endif>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">SĐT shipper</label>
                                <input type="tel" name="shipper_phone" value="{{ $order->shipper_phone }}"
                                    class="form-control bg-light" required
                                    @if ($order->status != 'shipped' && $order->status != 'processing') readonly @endif>
                            </div>
                            @if ($order->status == 'shipped' || $order->status == 'processing')
                                <button type="submit" class="btn btn-primary">Lưu thông tin shipper</button>
                            @endif
                        </form>

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
                                    @foreach ($order->details as $detail)
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
                                            <td class="text-end fw-bold">
                                                {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} VNĐ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <div>
                                    <tr class="table-active">
                                        <td colspan="3" class="text-end fw-bold">Phí vận chuyển:</td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($order->total, 0, ',', '.') }} VNĐ
                                        </td>
                                    </tr>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>
            <a href="{{ route('order.invoice', $order->id) }}" target="_blank"
                class="btn btn-success mt-3">
            <i class="fas fa-file-invoice"></i>
                Xuất hóa đơn
            </a>
            </div>
        </div>
    </div>
@endsection
