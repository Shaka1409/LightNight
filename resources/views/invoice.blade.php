<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>HÓA ĐƠN BÁN HÀNG</h2>
    <p><strong>Mã đơn:</strong> #{{ $order->id }}</p>
    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Người đặt:</strong> {{ $order->name }}</p>
    <p><strong>SĐT:</strong> {{ $order->phone }}</p>
    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>

    @if ($order->shipper_name)
        <p><strong>Shipper:</strong> {{ $order->shipper_name }} - {{ $order->shipper_phone }}</p>
    @endif

    <hr>
    <h3>Chi tiết sản phẩm:</h3>
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->price, 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} VNĐ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="text-align: right;">Tổng cộng: {{ number_format($order->total, 0, ',', '.') }} VNĐ</h3>

    <p style="margin-top: 30px;">Cảm ơn quý khách đã mua hàng tại <strong>{{ config('app.name', 'Light9') }}</strong>!</p>
</body>
</html>
