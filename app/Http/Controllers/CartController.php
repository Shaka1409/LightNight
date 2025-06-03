<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\CartRequest;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng.
     */
   public function index()
{
    session()->forget(['buy_now_item', 'checkout_type']);
    $cartItems = session()->get('cart', []);

    $totalPrice = 0;

    foreach ($cartItems as &$item) {
        // Lấy tồn kho mới nhất từ DB cho từng sản phẩm
        $product = Product::find($item['id']);

        $stock = $product ? $product->stock_quantity : 0;

        // Cập nhật tồn kho mới nhất cho item
        $item['stock_quantity'] = $stock;

        // Điều chỉnh số lượng theo tồn kho
        if ($stock == 0) {
            $item['quantity'] = 0;
        } elseif ($item['quantity'] > $stock) {
            $item['quantity'] = $stock;
        }

        $totalPrice += $item['price'] * $item['quantity'];
    }
    unset($item);

    // Cập nhật lại giỏ hàng session với tồn kho và số lượng mới nhất
    session()->put('cart', $cartItems);

    return view('cart', compact('cartItems', 'totalPrice'));
}




    /**
     * Thêm sản phẩm vào giỏ hàng.
     */
    public function add(CartRequest $request)
{
    // Lấy thông tin sản phẩm từ DB
    $product = Product::findOrFail($request->product_id);

    // Lấy giỏ hàng hiện tại từ session
    $cart = session()->get('cart', []);

    // Nếu sản phẩm đã tồn tại trong giỏ, tăng số lượng, ngược lại thêm mới
    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $request->quantity;
        $cart[$product->id]['stock_quantity'] = $product->stock_quantity;
    } else {
        $cart[$product->id] = [
            'id'       => $product->id,
            'name'     => $product->name,
            'price'    => $product->effective_price,
            'image'    => $product->image ?? 'default.png',
            'quantity' => $request->quantity,
        ];
    }

    // Lưu giỏ hàng vào session
    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công!',
    ]);
}
    

    /**
     * Cập nhật số lượng sản phẩm trong giỏ.
     */
    public function update(Request $request)
{
    $cart = session()->get('cart', []);
    $productId = $request->product_id;
    $quantity = max(1, intval($request->quantity)); // Đảm bảo số lượng >= 1

    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $quantity;
        session()->put('cart', $cart);
    }

    // Tính lại tổng tiền
    $totalPrice = array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $cart));

    return response()->json([
        'message' => 'Cập nhật giỏ hàng thành công!',
        'totalPrice' => number_format($totalPrice, 0, ',', '.') . ' VND'
    ]);
}


    /**
     * Xóa sản phẩm khỏi giỏ.
     */
    public function remove($id)
{
    // Lấy giỏ hàng từ session
    $cart = session()->get('cart', []);

    // Nếu sản phẩm tồn tại trong giỏ, xóa nó
    if (isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);
    }

    // Sau khi xóa, redirect quay lại trang giỏ hàng (hoặc trang trước)
    return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng!');
}


}
