<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Voucher;
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
            // ✅ Tính tổng tiền gốc + kiểm tra tồn kho
            $totalAmount = 0;
            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);

                if ($item['quantity'] > $product->stock) {
                    DB::rollBack();
                    return back()->with(
                        'error',
                        "Sản phẩm '{$product->name}' chỉ còn {$product->stock} sản phẩm trong kho."
                    )->withInput();
                }

                $totalAmount += $product->price * $item['quantity'];
            }

            // ✅ Xử lý voucher (nếu có)
            $voucherModel   = null;
            $voucherId      = null;
            $voucherCode    = null;
            $discountAmount = 0;

            $voucherSession = session()->get('voucher');
            if ($voucherSession) {
                $voucherModel = Voucher::find($voucherSession['id']);

                // Kiểm tra lại voucher còn hợp lệ không
                if ($voucherModel && $voucherModel->isValid($totalAmount)['valid']) {
                    $discountAmount = $voucherModel->calculateDiscount($totalAmount);
                    $voucherId      = $voucherModel->id;
                    $voucherCode    = $voucherModel->code;
                }
            }

            $finalAmount = $totalAmount - $discountAmount;

            // ✅ Tạo đơn hàng (với voucher + discount)
            $order = Order::create([
                'user_id'          => Auth::id(),
                'voucher_id'       => $voucherId,
                'voucher_code'     => $voucherCode,
                'discount_amount'  => $discountAmount,
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_email'   => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'total_amount'     => $finalAmount,
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

                $product->decrement('stock', $item['quantity']);
            }

            // ✅ Tăng số lần sử dụng voucher
            if ($voucherModel) {
                $voucherModel->increment('used_count');
            }

            DB::commit();

            // ✅ Xóa voucher khỏi session sau khi đặt hàng thành công
            session()->forget('voucher');

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

    // ==========================================
    // XEM CHI TIẾT ĐƠN HÀNG CỦA USER
    // ==========================================
    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load('details.product');
        return view('orders.show', compact('order'));
    }

    // ==========================================
    // ÁP DỤNG VOUCHER
    // ==========================================
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($request->voucher_code));
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return back()->with('voucher_error', 'Mã voucher không tồn tại.');
        }

        $cart = session()->get('cart', []);
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $check = $voucher->isValid($totalAmount);
        if (!$check['valid']) {
            return back()->with('voucher_error', $check['message']);
        }

        session()->put('voucher', [
            'id'       => $voucher->id,
            'code'     => $voucher->code,
            'name'     => $voucher->name,
            'type'     => $voucher->type,
            'value'    => $voucher->value,
            'discount' => $voucher->calculateDiscount($totalAmount),
        ]);

        return back()->with('voucher_success', 'Áp dụng voucher thành công!');
    }

    // ==========================================
    // HỦY VOUCHER
    // ==========================================
    public function removeVoucher()
    {
        session()->forget('voucher');
        return back()->with('voucher_success', 'Đã hủy áp dụng voucher.');
    }
}