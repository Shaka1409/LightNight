<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Order_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusChangedMail;
use App\Models\OrderStatusHistory;
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

        // Ghi log lịch sử thay đổi trạng thái
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => $currentStatus,
            'new_status' => $newStatus,
            'changed_at' => now(),
            'changed_by' => auth()->id(),
        ]);
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

    return redirect()->route('admin.orders.index')->with('success', 'Trạng thái đơn hàng đã được cập nhật và email đã được gửi.');
}
}
