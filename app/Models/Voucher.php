<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'type', 'value',
        'min_order_amount', 'max_discount', 'quantity',
        'used_count', 'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
    ];

    // ==========================================
    // KIỂM TRA VOUCHER CÓ HỢP LỆ KHÔNG
    // ==========================================
    public function isValid($orderAmount = 0)
    {
        // 1. Còn hoạt động
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Voucher đã bị vô hiệu hóa.'];
        }

        // 2. Còn trong thời gian hiệu lực
        $now = Carbon::now();
        if ($now->lt($this->start_date)) {
            return ['valid' => false, 'message' => 'Voucher chưa đến thời gian sử dụng.'];
        }
        if ($now->gt($this->end_date)) {
            return ['valid' => false, 'message' => 'Voucher đã hết hạn.'];
        }

        // 3. Còn số lượng
        if ($this->quantity > 0 && $this->used_count >= $this->quantity) {
            return ['valid' => false, 'message' => 'Voucher đã hết lượt sử dụng.'];
        }

        // 4. Đơn hàng đủ điều kiện
        if ($orderAmount > 0 && $orderAmount < $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($this->min_order_amount, 0, ',', '.') . 'đ mới áp dụng được voucher.'
            ];
        }

        return ['valid' => true, 'message' => 'Voucher hợp lệ.'];
    }

    // ==========================================
    // TÍNH SỐ TIỀN GIẢM
    // ==========================================
    public function calculateDiscount($orderAmount)
    {
        if ($this->type === 'percent') {
            $discount = $orderAmount * ($this->value / 100);

            // Giới hạn giảm tối đa
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }

            return $discount;
        }

        // Loại 'fixed'
        return min($this->value, $orderAmount);
    }
}