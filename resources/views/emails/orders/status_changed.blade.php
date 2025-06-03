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
# Xin chào {{ $order->user->name }},

Trạng thái đơn hàng **#{{ $order->id }}** của bạn đã được cập nhật.

- **Trạng thái cũ:** {{ $statusVN[$oldStatus] ?? $oldStatus }}
- **Trạng thái mới:** {{ $statusVN[$newStatus] ?? $newStatus }}

Cảm ơn bạn đã mua sắm tại {{ config('app.name') }}!

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
