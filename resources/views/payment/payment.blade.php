@extends('layout.app') 
@section('content')
<div class="container mt-5">
  <h1 class="text-3xl font-bold mb-4">Thanh Toán</h1>

  @if(isset($orderItems) && count($orderItems) > 0)
    <div class="bg-white shadow rounded-lg p-6">
      <!-- Danh sách sản phẩm -->
      @foreach($orderItems as $item)
        <div class="flex justify-between items-center border-b py-2">
          <div>
            <h2 class="font-bold text-lg">{{ $item['product']->name }}</h2>
            <p>Số lượng: {{ $item['quantity'] }}</p>
          </div>
          <div>
            <p class="text-xl font-semibold">
              {{ number_format($item['itemTotal'], 0, ',', '.') }} VND
            </p>
          </div>
        </div>
      @endforeach

      <!-- Tổng tiền -->
      <div class="text-right mt-4">
        <p class="text-2xl font-bold">
          Tổng tiền: {{ number_format($totalPrice, 0, ',', '.') }} VND
        </p>
      </div>

      <!-- Form xử lý thanh toán -->
      <form action="{{ route('payment.process') }}" method="POST" class="mt-6">
        @csrf
        <!-- Tùy theo logic, bạn có thể gửi lại danh sách sản phẩm hoặc ID đơn hàng -->
        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 transition">
          Thanh toán ngay
        </button>
      </form>
    </div>
  @else
    <p>Không có thông tin đơn hàng.</p>
  @endif
</div>
@endsection
