<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ==========================================
    // BƯỚC 1: FORM NHẬP THÔNG TIN GIAO HÀNG
    // ==========================================
    public function checkoutForm()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        return view('checkout.index', compact('cart'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Validate thông tin người nhận
        $validated = $request->validate([
            'fullname'         => 'required|string|max:255',
            'phone'            => 'required|regex:/^[0-9]{10,11}$/',
            'shipping_address' => 'required|string|min:10|max:500',
            'note'             => 'nullable|string|max:1000',
        ], [
            'fullname.required'         => 'Vui lòng nhập tên người nhận hàng.',
            'phone.required'            => 'Vui lòng nhập số điện thoại nhận hàng.',
            'phone.regex'               => 'Số điện thoại phải từ 10 đến 11 số.',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng chi tiết.',
            'shipping_address.min'      => 'Địa chỉ giao hàng quá ngắn (tối thiểu 10 ký tự).',
        ]);

        DB::beginTransaction();
        try {
            // Tính tổng tiền giỏ hàng
            $totalAmount = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));

            // Tạo Đơn hàng tạm thời (chờ bước chọn thanh toán)
            $order = Order::create([
                'user_id'          => Auth::id(),
                'fullname'         => $validated['fullname'],
                'phone'            => $validated['phone'],
                'shipping_address' => $validated['shipping_address'],
                'note'             => $validated['note'] ?? null,
                'total_amount'     => $totalAmount,
                'order_status'     => 'pending',
                'payment_method'   => null, // Sẽ cập nhật ở Bước 2
            ]);

            // Lưu danh sách chi tiết sản phẩm
            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            DB::commit();

            // Chuyển hướng sang Bước 2: Chọn thanh toán
            return redirect()->route('checkout.payment', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi trong quá trình xử lý: ' . $e->getMessage())->withInput();
        }
    }

    // ==========================================
    // BƯỚC 2: PHƯƠNG THỨC THANH TOÁN
    // ==========================================
    public function paymentForm($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        return view('checkout.payment', compact('order'));
    }

    public function processPayment(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'payment_method' => 'required|in:cod,vnpay,momo,bank_transfer',
        ], [
            'payment_method.required' => 'Vui lòng chọn hình thức thanh toán.',
            'payment_method.in'       => 'Phương thức thanh toán không hợp lệ.',
        ]);

        // Cập nhật phương thức thanh toán vào DB
        $order->update([
            'payment_method' => $validated['payment_method'],
        ]);

        // Xóa giỏ hàng sau khi hoàn tất chọn thanh toán
        session()->forget('cart');

        // Chuyển hướng sang Bước 3: Hoàn tất đơn hàng
        return redirect()->route('checkout.success', $order->id);
    }

    // ==========================================
    // BƯỚC 3: TRANG HOÀN TẤT ĐƠN HÀNG
    // ==========================================
    public function success($id)
    {
        $order = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($id);

        return view('checkout.success', compact('order'));
    }

    // ==========================================
    // LỊCH SỬ ĐƠN HÀNG CÁ NHÂN
    // ==========================================
    public function mine()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(5);

        return view('orders.mine', compact('orders'));
    }
}