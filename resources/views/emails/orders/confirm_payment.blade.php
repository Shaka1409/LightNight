{{-- xác nhận thanh toán --}}
@component('mail::message')
# Xác nhận thanh toán đơn hàng #{{ $order->id }}

Cảm ơn bạn đã thanh toán đơn hàng tại **{{ config('app.name') }}**.

**Thông tin đơn hàng:**

- Tên người nhận: {{ $order->name }}
- SĐT: {{ $order->phone }}
- Địa chỉ: {{ $order->address }}
- Phương thức thanh toán: {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng' }}
- Tổng tiền: {{ number_format($order->total, 0, ',', '.') }} ₫
- Ngày đặt hàng: {{ $order->created_at->format('d/m/Y H:i') }}

@if ($order->shipper_name)
- Shipper: {{ $order->shipper_name }} - {{ $order->shipper_phone }}
@endif

@component('mail::button', ['url' => url('/order/' . $order->id)])
Xem đơn hàng
@endcomponent

Trân trọng,  
**{{ config('app.name') }}**
@endcomponent
