<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        // Validate thông tin thanh toán, ví dụ: order_id và phương thức thanh toán
        $validated = $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'payment_method' => 'required|string', // ví dụ: "credit_card", "vnpay", v.v.
        ]);

        // Lấy đơn hàng theo order_id
        $order = Orders::findOrFail($validated['order_id']);

        // Giả sử gọi API thanh toán và xử lý kết quả thanh toán
        // Ở đây ta mô phỏng thanh toán thành công:
        $paymentSuccess = true;

        if ($paymentSuccess) {
            // Cập nhật trạng thái đơn hàng, ví dụ: chuyển từ 'pending' sang 'processing' hoặc 'paid'
            $order->update([
                'status' => 'paid',
            ]);

            // Chuyển hướng đến trang thành công và truyền thông tin đơn hàng qua session flash
            return redirect()->route('payment.success')->with([
                'orderId' => $order->id,
                'total'   => $order->total,
                'message' => 'Thanh toán thành công cho đơn hàng #' . $order->id,
            ]);
        } else {
            Log::error("Thanh toán thất bại cho đơn hàng #" . $order->id);
            return redirect()->back()->with('error', 'Thanh toán không thành công, vui lòng thử lại.');
        }
    }

    public function success()
    {
        // Lấy thông tin đơn hàng từ session
        $orderId = session('orderId');
        $total   = session('total');
        $message = session('message');

        return view('payment.success', compact('orderId', 'total', 'message'));
    }
}
