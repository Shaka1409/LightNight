<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\Auth\CustomForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Auth::routes();

Route::get('/', function () {
    return redirect('/home');
});
Route::get('/home', [PagesController::class, 'index'])->name('home');

Route::get('/news', [PagesController::class, 'news'])->name('news');

Route::get('/product', [PagesController::class, 'show'])->name('product.show');

Route::get('/san-pham/{slug}', [PagesController::class, 'detail'])->name('product.detail');


Route::get('/search', [PagesController::class, 'search'])->name('search');

Route::get('/products/category/{id}', [PagesController::class, 'productsByCategory'])->name('products.category');

Route::middleware('guest')->group(function () {

    // Hiển thị form đăng nhập
    Route::get('/login', [LoginController::class, 'formLogin'])->name('login');

    // Xử lý đăng nhập (POST)
    Route::post('/login', [LoginController::class, 'login']);

    // Hiển thị form đăng ký
    Route::get('/register', [RegisterController::class, 'register'])->name('register');

    // Xử lý đăng ký (POST)
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/password/reset', [CustomForgotPasswordController::class, 'showOtpRequestForm'])->name('otp.request');
    Route::post('/password/reset', [CustomForgotPasswordController::class, 'sendOtp'])->name('otp.send');

    Route::get('/password/verify', [CustomForgotPasswordController::class, 'showOtpVerifyForm'])->name('otp.verify.form');
    Route::post('/password/verify', [CustomForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');

    Route::get('/password/new', [CustomForgotPasswordController::class, 'showResetForm'])->name('otp.new.form');
    Route::post('/password/new', [CustomForgotPasswordController::class, 'resetPassword'])->name('otp.reset');
});

Route::get('/new/{id}', [PagesController::class, 'new'])->name('new');

Route::get('/contact', [ContactController::class, 'contact'])->name('contact');

Route::post('/contact/send', [ContactController::class, 'sendContact'])->name('contact.send');


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');

    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'view'])->name('checkout.view');

    Route::post('/checkout/buy-now', [CheckoutController::class, 'buyNow'])->name('checkout.buyNow');

    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');

    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/my-orders', [OrderDetailsController::class, 'index'])->name('user.orders');

    Route::get('/orders/{id}/invoice', [OrderDetailsController::class, 'exportInvoice'])->name('order.invoice');

    Route::put('/order/{order}/cancel', [OrderDetailsController::class, 'cancel'])->name('order.cancel');

    Route::delete('/orders/{order}/delete', [OrderDetailsController::class, 'deleteCancelledOrder'])->name('order.delete');

    Route::resource('comments', CommentController::class)->only([
        'store',
        'destroy',
    ]);
});

Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
    
    // Trang Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Trang quản lý Banner
    Route::resource('/banners', BannersController::class)->names('banners');

    // Resource cho Category
    Route::resource('/category', CategoryController::class)->names('category');

    // Resource cho User
    Route::resource('/users', UserController::class)->names('user');

 Route::delete('/products/delete-sub-image/{id}', [ProductController::class, 'deleteSubImage'])->name('product.deleteSubImage');

    // Resource cho Product
    Route::resource('/products', ProductController::class)->names('product');

    // Resource cho news (thêm, sửa, xoá news)
    Route::resource('/news', NewsController::class)->names('news');

    // Danh sách bình luận
    Route::get('/comments', [CommentController::class, 'index'])->name('admin.comments.index');

    // Xóa bình luận
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('admin.comments.destroy');

    Route::put('/comments/{comment}', [CommentController::class, 'updateStatus'])->name('admin.comments.updateStatus');

    Route::get('/orders', [OrdersController::class, 'index'])->name('admin.orders.index');

    Route::get('/orders/{id}', [OrdersController::class, 'show'])->name('admin.orders.show');

    Route::put('/orders/{order}/confirm-payment', [OrdersController::class, 'confirmPayment'])->name('admin.orders.confirmPayment');

    Route::patch('/orders/{id}/status', [OrdersController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    Route::put('/admin/orders/{id}/shipper', [OrdersController::class, 'updateShipper'])->name('admin.orders.updateShipper');
});
