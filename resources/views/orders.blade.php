@extends('layout.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center"> Đơn Hàng Của Tôi</h1>

        @if ($orders->isEmpty())
            <div class="flex flex-col items-center bg-white shadow-lg rounded-lg p-6">
                <p class="text-gray-600 text-lg mb-4">Bạn chưa có đơn hàng nào.</p>
                <a href="{{ url('/product') }}"
                    class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md sm:hover:bg-blue-600 transition">
                    Khám Phá Sản Phẩm
                </a>
            </div>
        @else
            @foreach ($orders as $order)
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <!-- Thông tin đơn hàng -->
                    <div class="grid grid-cols-2 gap-4 items-center border-b pb-3 mb-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Mã đơn: #{{ $order->id }}</h2>
                            <p class="text-gray-600 text-sm">Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</p>

                            @php
                                $status_classes = [
                                    'pending' => 'bg-yellow-200 text-yellow-700',
                                    'processing' => 'bg-blue-200 text-blue-700',
                                    'shipped' => 'bg-green-200 text-green-700',
                                    'delivered' => 'bg-gray-200 text-gray-700',
                                    'cancelled' => 'bg-red-200 text-red-700',
                                ];
                                $status_labels = [
                                    'pending' => 'Chờ xác nhận',
                                    'processing' => 'Đang xử lý',
                                    'shipped' => 'Đang giao',
                                    'delivered' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                ];
                            @endphp

                            <span
                                class="px-2 py-1 text-sm font-semibold rounded {{ $status_classes[$order->status] ?? 'bg-gray-200 text-gray-700' }}">
                                {{ $status_labels[$order->status] ?? 'Không xác định' }}
                            </span>
                        </div>

                        <div class="text-right">
                            <h3 class="text-lg font-bold text-gray-900">Tổng giá:
                                {{ number_format($order->total, 0, ',', '.') }} VNĐ
                            </h3>
                            <p class="text-sm text-gray-700">Phí vận chuyển:
                                {{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 text-sm text-gray-700 mb-3">
                        <p><strong>Người đặt:</strong> {{ $order->name }}</p>
                        <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>

                        @if ($order->shipper_name && $order->shipper_phone)
                            <p><strong>Shipper:</strong> {{ $order->shipper_name }}</p>
                            <p><strong>SĐT Shipper:</strong> {{ $order->shipper_phone }}</p>
                            <div></div>
                        @endif
                    </div>


                    <div class="flex justify-between items-center">
                        @if ($order->status === 'pending' || $order->status === 'processing')
                            <form action="{{ route('order.cancel', $order->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded shadow sm:hover:bg-red-600 transition text-sm">
                                    Hủy đơn
                                </button>
                            </form>
                        @elseif ($order->status === 'shipped')
                            <span class="text-indigo-600 font-semibold text-sm">Đang giao hàng</span>
                        @elseif ($order->status === 'delivered')
                            <span class="text-green-600 font-semibold text-sm">Đã giao hàng thành công</span>
                            <div class="text-red-500 font-semibold text-sm">
                                <form action="{{ route('order.delete', $order->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded shadow sm:hover:bg-red-600 transition text-sm">
                                        Xóa đơn hàng
                                    </button>
                                </form>
                            </div>
                        @elseif ($order->status === 'cancelled')
                            <div class="text-red-500 font-semibold text-sm">
                                <form action="{{ route('order.delete', $order->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded shadow sm:hover:bg-red-600 transition text-sm">
                                        Xóa đơn hàng
                                    </button>
                                </form>
                            </div>
                        @endif
                        {{-- Nút xuất hóa đơn --}}
    <a href="{{ route('order.invoice', $order->id) }}"
       class="bg-green-500 text-white px-3 py-1 rounded shadow hover:bg-green-600 transition text-sm">
        Xuất hóa đơn
    </a>
                    </div>


                    <!-- Danh sách sản phẩm trong đơn -->
                    @foreach ($order->details as $detail)
                        <div class="flex items-center space-x-4 border-b py-4">
                            <img src="{{ asset('storage/' . $detail->product->image) }}"
                                alt="{{ $detail->product->name }}" class="w-16 h-16 object-cover rounded shadow">
                            <div class="flex-1">
                                <h3 class="text-gray-800 font-semibold">{{ $detail->product->name }}</h3>
                                <p class="text-gray-600 text-sm">Số lượng: {{ $detail->quantity }}</p>
                            </div>
                            <p class="text-gray-900 font-semibold">
                                {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} VNĐ
                            </p>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
@endsection
