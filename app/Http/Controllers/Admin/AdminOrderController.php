<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    // ==========================================
    // DANH SÁCH ĐƠN HÀNG
    // ==========================================
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // ==========================================
    // CHI TIẾT ĐƠN HÀNG
    // ==========================================
    public function show(Order $order)
    {
        $order->load(['user', 'details.product']);
        return view('admin.orders.show', compact('order'));
    }

    // ==========================================
    // CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
    // ==========================================
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,shipping,completed,cancelled',
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $validated['order_status'];

        // ✅ Nếu trạng thái không đổi → không làm gì
        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Trạng thái đơn hàng không thay đổi.');
        }

        DB::beginTransaction();
        try {
            // ✅ ROLLBACK TỒN KHO: Hủy đơn → cộng lại stock
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->details as $detail) {
                    if ($detail->product) {
                        $detail->product->increment('stock', $detail->quantity);
                    }
                }
            }

            // ✅ ROLLBACK TỒN KHO: Từ cancelled → trạng thái khác → trừ lại stock
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                // Kiểm tra tồn kho trước khi trừ
                foreach ($order->details as $detail) {
                    if ($detail->product && $detail->product->stock < $detail->quantity) {
                        DB::rollBack();
                        return back()->with(
                            'error',
                            "Không thể khôi phục đơn hàng: sản phẩm '{$detail->product_name}' không đủ tồn kho."
                        );
                    }
                }

                foreach ($order->details as $detail) {
                    if ($detail->product) {
                        $detail->product->decrement('stock', $detail->quantity);
                    }
                }
            }

            // Cập nhật trạng thái đơn hàng
            $order->update([
                'order_status' => $newStatus,
            ]);

            DB::commit();
            return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
}