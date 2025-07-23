<?php

namespace App\Http\Controllers;

use App\Models\Order_details;
use App\Models\Orders;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCancelledByUserMail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderReportCancellForShopMail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderDetailsController extends Controller
{
    public function index()
    {
        $orders = Orders::where('user_id', auth()->id())
            ->where('hidden_by_user', false)
            ->with('details.product')
            ->get();
        return view('orders', compact('orders'));
    }

    public function exportInvoice($id)
    {
        $order = Orders::with('details.product')->findOrFail($id);
   // Nếu người dùng KHÔNG phải admin
    if (!auth()->user()->is_admin) {
        // Và đơn hàng KHÔNG thuộc về họ thì chặn
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền truy cập hoá đơn này.');
        }
    }
        return Pdf::loadView('invoice', compact('order'))
            ->stream("invoice_order_{$order->id}.pdf");
    }

    public function cancel(Orders $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn hàng này.');
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể hủy đơn hàng khi đang chờ xác nhận hoặc đang xử lý.');
        }

        DB::transaction(function () use ($order) {
            $order->load('details.product');

            foreach ($order->details as $detail) {
                $product = $detail->product;
                if ($product) {
                    $product->stock_quantity += $detail->quantity;
                    $product->sold_count -= $detail->quantity;
                    if ($product->sold_count < 0) {
                        $product->sold_count = 0;
                    }
                    $product->save();
                }
            }

            $order->update(['status' => 'cancelled']);
        });

        // Gửi email thông báo hủy đơn
        try {
            Mail::to($order->user->email)->queue(new OrderCancelledByUserMail($order));

            // Gửi bản báo cáo từ shop về địa chỉ mail từ .env
            Mail::to(config('mail.from.address'))->queue(new OrderReportCancellForShopMail($order));
        } catch (\Exception $e) {
            Log::error("Lỗi gửi email hủy đơn hàng bởi user: " . $e->getMessage());
        }

        return redirect()->route('user.orders')->with('success', 'Đơn hàng đã được hủy và sản phẩm được hoàn về kho.');
    }


       
    public function deleteCancelledOrder(Orders $order)
    {
        // Kiểm tra quyền sở hữu đơn hàng
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đơn hàng này.');
        }
    
        // Chỉ cho phép ẩn đơn hàng nếu trạng thái là "cancelled" hoặc "delivered"
        if ($order->status !== 'cancelled' && $order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Chỉ có thể ẩn đơn hàng đã hủy hoặc đã nhận.');
        }
    
        // Đánh dấu đơn hàng đã bị ẩn bởi user
        $order->hidden_by_user = true;
        $order->save();
    
        return redirect()->back()->with('success', 'Đơn hàng đã được xoá khỏi lịch sửa của bạn.');
    }
}
