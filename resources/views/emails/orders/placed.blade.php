@php
$statusVN = [
    'pending' => 'Chờ xử lý',
    'processing' => 'Đang xử lý',
    'shipped' => 'Đã giao hàng',
    'delivered' => 'Đã nhận hàng',
    'cancelled' => 'Đã hủy',
];
@endphp

@component('mail::message')
# Xin chào {{ $order->name }},

Cảm ơn bạn đã đặt hàng tại {{ config('app.name') }}.  
Chúng tôi đã nhận được đơn hàng **#{{ $order->id }}** của bạn.

**Thông tin đơn hàng:**

- Tên người nhận: {{ $order->name }}
- SĐT: {{ $order->phone }}
- Địa chỉ: {{ $order->address }}
- Tổng tiền: {{ number_format($order->total, 0, ',', '.') }}₫
- Trạng thái: {{ $statusVN[$order->status] ?? $order->status }}
- Ngày đặt hàng: {{ $order->created_at->format('d/m/Y H:i') }}


Chúng tôi sẽ liên hệ với bạn để xác nhận và xử lý đơn hàng sớm nhất có thể.

Trân trọng,  
{{ config('app.name') }}
@endcomponent
