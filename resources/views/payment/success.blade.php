@extends('layout.app')
@section('content')
<div class="container mt-5">
  <h1 class="text-3xl font-bold mb-4">Thanh Toán Thành Công</h1>
  @if(isset($message))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
      {{ $message }}
    </div>
  @endif
  <div class="bg-white shadow rounded-lg p-6">
    <p class="text-xl">Mã đơn hàng: <strong>{{ $orderId }}</strong></p>
    <p class="text-xl">Số tiền thanh toán: <strong>{{ number_format($total, 0, ',', '.') }} VND</strong></p>
  </div>
</div>
@endsection
