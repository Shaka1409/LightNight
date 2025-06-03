@component('mail::message')
# khách hàng {{ $order->user->name }}

đã hủy đơn hàng **#{{ $order->id }}** thành công.

Chúng tôi đã ghi nhận trạng thái đơn hàng.

Trân trọng,
{{ config('app.name') }}
@endcomponent