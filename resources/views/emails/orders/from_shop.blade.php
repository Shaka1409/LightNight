@component('mail::message')
# Báo cáo đơn hàng từ cửa hàng

Đơn hàng mới **#{{ $order->id }}** đã được đặt.

**Thông tin đơn hàng:**
- Người đặt: {{ $order->user->name }}
- Người nhận: {{ $order->name }}
- Email: {{ $order->user->email ?? 'N/A' }}
- SĐT: {{ $order->phone }}
- Địa chỉ: {{ $order->address }}
- Phương thức thanh toán: {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng' }}  

**Tổng tiền:** {{ number_format($order->total, 0, ',', '.') }}₫  
**Thời gian:** {{ $order->created_at->format('d/m/Y H:i') }}

@component('mail::button', ['url' => route('admin.orders.show', $order->id)])
Xem đơn hàng
@endcomponent

Trân trọng,  
{{ config('app.name') }}
@endcomponent
