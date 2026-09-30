<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'shipping_address' => 'required|string|max:500|min:10',
        ], [
            'customer_name.required'    => 'Vui lòng nhập họ tên.',
            'customer_phone.required'   => 'Vui lòng nhập số điện thoại.',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'shipping_address.min'      => 'Địa chỉ giao hàng quá ngắn (tối thiểu 10 ký tự).',
        ]);

        DB::beginTransaction();
        try {
            // ✅ Lấy giá từ DB + kiểm tra tồn kho
            $totalAmount = 0;
            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);

                // ✅ Kiểm tra tồn kho
                if ($item['quantity'] > $product->stock) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        "Sản phẩm '{$product->name}' chỉ còn {$product->stock} sản phẩm trong kho."
                    )->withInput();
                }

                $totalAmount += $product->price * $item['quantity'];
            }

            // Tạo đơn hàng
            $order = Order::create([
                'user_id'          => Auth::id(),
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_email'   => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'total_amount'     => $totalAmount,
                'payment_method'   => 'cod',
                'payment_status'   => 'unpaid',
                'order_status'     => 'pending',
            ]);

            // ✅ Lưu chi tiết + trừ tồn kho
            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);

                OrderDetail::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'quantity'     => $item['quantity'],
                    'price'        => $product->price,
                    'subtotal'     => $product->price * $item['quantity'],
                ]);

                // ✅ Trừ tồn kho
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return redirect()->route('checkout.payment', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
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
            'payment_method' => 'required|in:cod,qr',
        ], [
            'payment_method.required' => 'Vui lòng chọn hình thức thanh toán.',
            'payment_method.in'       => 'Phương thức thanh toán không hợp lệ.',
        ]);

        $order->update([
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_method'] === 'cod' ? 'unpaid' : 'pending',
        ]);

        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id);
    }

    // ==========================================
    // BƯỚC 3: TRANG HOÀN TẤT ĐƠN HÀNG
    // ==========================================
    public function success($id)
    {
        $order = Order::with('details.product')->where('user_id', Auth::id())->findOrFail($id);
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