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
        // Lấy danh sách đơn hàng của user hiện tại, bao gồm thông tin sản phẩm trong đơn
        $orders = Orders::where('user_id', Auth::id())
            ->with(['details.product']) // Lấy thông tin sản phẩm trong đơn
            ->latest()
            ->get();

        return view('orders', compact('orders'));
    }

    public function exportInvoice($id)
    {
        $order = Orders::with('details.product')->findOrFail($id);

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

        // Chỉ cho phép xóa đơn hàng nếu trạng thái là "cancelled"
        if ($order->status !== 'cancelled' && $order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy hoặc đã nhận.');
        }

        // Xóa đơn hàng
        $order->delete();

        return redirect()->back()->with('success', 'Đơn hàng đã được xóa thành công.');
    }
}
