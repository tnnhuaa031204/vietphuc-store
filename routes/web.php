<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes - Hoa Nghiêm Việt Phục
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. TRANG CÔNG KHAI (GUEST & ALL USERS)
// ==========================================

// Trang chủ & Chi tiết sản phẩm
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Quản lý giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');


// ==========================================
// 2. XÁC THỰC TÀI KHOẢN (GUEST ONLY)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// Route Đăng nhập & Đăng xuất
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// 3. KHU VỰC CẦN ĐĂNG NHẬP (AUTH USER)
// ==========================================
Route::middleware('auth')->group(function () {
    // Quy trình thanh toán (Checkout)
    Route::get('/checkout', [OrderController::class, 'checkoutForm'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');

    // Chọn phương thức thanh toán & Xác nhận
    Route::get('/checkout/payment/{id}', [OrderController::class, 'paymentForm'])->name('checkout.payment');
    Route::post('/checkout/payment/{id}', [OrderController::class, 'processPayment'])->name('checkout.payment.process');

    // Đặt hàng thành công
    Route::get('/checkout/success/{id}', [OrderController::class, 'success'])->name('checkout.success');

    // Lịch sử đơn hàng cá nhân
    Route::get('/my-orders', [OrderController::class, 'mine'])->name('orders.mine');
   
    // Xem chi tiết đơn hàng của user
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');

});


// ==========================================
// 4. KHU VỰC QUẢN TRỊ (ADMIN ONLY)
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    // Trang Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý đơn hàng
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');

    // Quản lý sản phẩm (CRUD đầy đủ)
    Route::resource('products', AdminProductController::class);
});

Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});