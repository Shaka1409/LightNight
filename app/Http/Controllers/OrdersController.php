<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Order_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusChangedMail;
use App\Mail\confirmPayment;
use Illuminate\Support\Facades\Log;


class OrdersController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        $query = Orders::query();

        if ($request->filled('q')) {
            $keyword = $request->q;

            $query->where(function ($q) use ($keyword) {
                $q->where('id', $keyword) // tìm theo ID
                    ->orWhere('name', 'like', "%$keyword%") // tên người nhận
                    ->orWhere('phone', 'like', "%$keyword%"); // số điện thoại
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }
    public function store(Request $request)
    {
        $order = Orders::create([
            'user_id' => auth()->id(),
            'status'  => 'pending',
        ]);

        foreach ($request->products as $product) {
            Order_Details::create([
                'order_id'   => $order->id,
                'product_id' => $product['id'],
                'quantity'   => $product['quantity'],
                'price'      => $product['price'],
            ]);
        }

        return response()->json(['message' => 'Tạo đơn hàng thành công'], 201);
    }


    // Hiển thị chi tiết đơn hàng
    public function show($id)
    {
        // Load quan hệ orderDetails và user (và sản phẩm của từng order detail)
        $order = Orders::with('details.product', 'user')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng từ dropdown
    public function updateStatus(Request $request, $id)
    {
        $order = Orders::with('user', 'details.product')->findOrFail($id);

        // Validate trạng thái hợp lệ
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $newStatus = $request->status;
        $currentStatus = $order->status;

        // Trạng thái đã hoàn thành hoặc hủy thì không cho cập nhật
        if (in_array($currentStatus, ['cancelled', 'delivered'])) {
            return redirect()->back()->with('error', 'Đơn hàng đã hoàn thành hoặc bị hủy, không thể thay đổi trạng thái.');
        }

        // Quy tắc chuyển trạng thái hợp lệ
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
        ];

        if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
            return redirect()->back()->with('error', 'Chuyển trạng thái không hợp lệ.');
        }

        // Bắt đầu xử lý cập nhật trạng thái
        DB::transaction(function () use ($order, $newStatus, $currentStatus) {

            // Nếu hủy đơn => hoàn lại hàng
            if ($newStatus === 'cancelled') {
                foreach ($order->details as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->stock_quantity += $detail->quantity;
                        $product->sold_count = max(0, $product->sold_count - $detail->quantity);
                        $product->save();
                    }
                }
            }

            // Cập nhật trạng thái đơn
            $order->update(['status' => $newStatus]);

        });

        // Gửi email thông báo đến người dùng
        try {
            Mail::to($order->user->email)->queue(
                new OrderStatusChangedMail($order, $currentStatus, $newStatus)
            );
        } catch (\Exception $e) {
            // Ghi log nếu cần hoặc hiển thị cảnh báo
            Log::error("Gửi email thất bại: " . $e->getMessage());
        }

        if ($newStatus === 'shipped') {
            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('info', 'Đơn hàng đang được giao. Vui lòng cập nhật thông tin shipper nếu chưa có.');
        }

        return redirect()->route('admin.orders.index')->with('success', 'Trạng thái đơn hàng đã được cập nhật và email đã được gửi.');
    }
    public function updateShipper(Request $request, $id)
    {
        $request->validate([
            'shipper_name' => 'required|string|max:255',
            'shipper_phone' => 'required|string|max:20',
        ]);

        $order = Orders::findOrFail($id);
        $order->update([
            'shipper_name' => $request->shipper_name,
            'shipper_phone' => $request->shipper_phone,
        ]);

        return back()->with('success', 'Cập nhật thông tin shipper thành công.');
    }

    public function confirmPayment($id)
{
    $order = Orders::findOrFail($id);

    if ($order->is_paid) {
        return back()->with('error', 'Đơn hàng đã được xác nhận thanh toán.');
    }

    $order->is_paid = 1;

    // Cập nhật trạng thái nếu cần
    // if ($order->status !== 'delivered') {
    //     $order->status = 'delivered';
    // }

    $order->save();

    // Gửi email thông báo đến người dùng
    try {
        Mail::to($order->user->email)->queue(
            new ConfirmPayment($order) // Tên class viết hoa đúng chuẩn
        );
    } catch (\Exception $e) {
        Log::error("Gửi email thất bại: " . $e->getMessage());
    }

    return back()->with('success', 'Đã xác nhận thanh toán cho đơn hàng #' . $order->id);
}

}
