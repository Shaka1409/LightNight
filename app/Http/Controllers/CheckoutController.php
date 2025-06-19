<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Orders;
use App\Models\Order_details;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;
use App\Mail\OrderReportFromShopMail;

class CheckoutController extends Controller
{
    public function view(Request $request)
    {
        $cartItems = session('cart', []); // Giữ nguyên giỏ hàng
        $buyNowItem = session('buy_now_item', null); // Lấy sản phẩm mua ngay từ session
        $checkoutType = session('checkout_type', 'cart'); // Mặc định là thanh toán giỏ hàng

        $totalPrice = 0;
        if ($checkoutType === 'buy_now' && $buyNowItem) {
            $cartItems = [$buyNowItem]; // Nếu là mua ngay, chỉ hiển thị sản phẩm mua ngay
            $totalPrice = $buyNowItem['price'] * $buyNowItem['quantity']; //  Cập nhật tổng giá
        } else {
            // Lọc sản phẩm quantity > 0
            $cartItems = array_filter($cartItems, function ($item) {
                return $item['quantity'] > 0;
            });
            foreach ($cartItems as &$item) {
                $product = Product::find($item['id']);
                if ($product) {
                    $item['image'] = $product->image;
                }
                $totalPrice += $item['price'] * $item['quantity'];
            }
        }

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Không có sản phẩm để đặt mua.');
        }
        return view('checkout.view', compact('cartItems', 'totalPrice'));
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = Product::findOrFail($productId);
        $price = ($product->sale_price && $product->sale_price < $product->price)
            ? $product->sale_price
            : $product->price;

        $buyNowItem = [
            'id'       => $product->id,
            'name'     => $product->name,
            'quantity' => $quantity,
            'price'    => $price,
            'image'    => $product->image,
        ];

        // Lưu vào session
        session(['checkout_type' => 'buy_now', 'buy_now_item' => $buyNowItem]);

        return redirect()->route('checkout.view');
    }


    public function confirm(CheckoutRequest $request)
{
    $validated = $request->validated();
    $checkoutType = session('checkout_type', 'cart');

    $cartItems = ($checkoutType === 'buy_now')
        ? [session('buy_now_item', [])]
        : session('cart', []);

    if (empty($cartItems)) {
        return redirect()->route('checkout.view')->with('error', 'Giỏ hàng của bạn trống.');
    }

    $totalProductPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));

    // Phí vận chuyển theo khu vực
    $shippingFee = match ($request->input('shipping_area')) {
        'hanoi' => 0,
        'mienbac' => 30000,
        'toanquoc' => 50000,
    };

    $totalPrice = $totalProductPrice + $shippingFee;

    DB::beginTransaction();
    try {
        $order = Orders::create([
            'user_id'         => Auth::id() ?? null,
            'name'            => $validated['name'],
            'address'         => $validated['address'],
            'phone'           => $validated['phonenumber'],
            'total'           => $totalPrice,
            'status'          => 'pending',
            'payment_method' => $validated['payment_method'],
            'shipping_area'   => $request->input('shipping_area'),
            'shipping_fee'    => $shippingFee,
        ]);

        // Lưu ảnh chuyển khoản nếu có
        if ($request->hasFile('payment_proof')) {
            $paths = [];
            foreach ($request->file('payment_proof') as $file) {
                $paths[] = $file->store('payment_proofs', 'public');
            }
            $order->payment_proof = json_encode($paths);
            $order->save();
        }

        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);

            if ($product && $product->stock_quantity >= $item['quantity']) {
                $product->stock_quantity -= $item['quantity'];
                $product->sold_count += $item['quantity'];
                $product->save();

                Order_details::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            } else {
                DB::rollBack();
                return redirect()->route('checkout.view')
                    ->with('error', 'Sản phẩm "' . $product->name . '" không đủ số lượng trong kho.');
            }
        }

        DB::commit();

        try {
            if ($order->user) {
                Mail::to($order->user->email)->queue(new OrderPlacedMail($order));
            }

            Mail::to(config('mail.from.address'))->queue(new OrderReportFromShopMail($order));
        } catch (\Exception $e) {
            Log::error('Lỗi gửi mail: ' . $e->getMessage());
        }

        if ($checkoutType === 'cart') {
            session()->forget('cart');
        }

        session()->forget(['checkout_type', 'buy_now_item']);

        return redirect()->route('checkout.success')->with([
            'orderId' => $order->id,
            'total'   => $order->total,
            'message' => 'Đặt hàng thành công cho đơn hàng #' . $order->id,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('checkout.view')->with('error', 'Lỗi khi đặt hàng: ' . $e->getMessage());
    }
}






    public function success()
    {
        $orderId = session('orderId');
        $total = session('total');
        $message = session('message');
        return view('checkout.success', compact('orderId', 'total', 'message'));
    }
}
