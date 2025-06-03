@component('mail::message')
# Xin chào {{ $order->user->name }},

Bạn đã hủy đơn hàng **#{{ $order->id }}** thành công.

Chúng tôi đã ghi nhận trạng thái đơn hàng.

Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ bộ phận hỗ trợ.

Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.
<br>

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent
