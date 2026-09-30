<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SePayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Xác thực API Key từ header Authorization
        $authHeader = $request->header('Authorization');
        $expectedKey = 'Apikey ' . config('services.sepay.webhook_token');

        if ($authHeader !== $expectedKey) {
            Log::warning('SePay webhook: Unauthorized', ['header' => $authHeader]);
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // 2. Lấy dữ liệu
        $data = $request->all();
        Log::info('SePay webhook received', $data);

        // 3. Chỉ xử lý tiền vào
        if (($data['transferType'] ?? '') !== 'in') {
            return response()->json(['success' => true]);
        }

        // 4. Trích xuất mã đơn hàng từ nội dung chuyển khoản
        // Nội dung ví dụ: "DH123 chuyen tien"
        $content = $data['content'] ?? '';
        if (preg_match('/DH(\d+)/', $content, $matches)) {
            $orderId = $matches[1];
            $order = Order::find($orderId);

            if (!$order) {
                Log::warning('SePay webhook: Order not found', ['order_id' => $orderId]);
                return response()->json(['success' => true]);
            }

            // 5. Kiểm tra số tiền chuyển >= tổng tiền đơn hàng
            $transferAmount = $data['transferAmount'] ?? 0;
            if ($transferAmount >= $order->total_amount) {
                // Tránh cập nhật trùng lặp
                if ($order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'order_status'   => 'confirmed',
                    ]);
                    Log::info('SePay webhook: Order updated to paid', ['order_id' => $orderId]);
                }
            } else {
                Log::warning('SePay webhook: Amount mismatch', [
                    'order_id' => $orderId,
                    'expected' => $order->total_amount,
                    'received' => $transferAmount,
                ]);
            }
        }

        // 6. BẮT BUỘC trả về đúng định dạng này
        return response()->json(['success' => true]);
    }
}