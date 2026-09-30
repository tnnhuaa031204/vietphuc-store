<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('vouchers', function (Blueprint $table) {
        $table->id();
        $table->string('code', 50)->unique();           // Mã voucher (VD: SALE10)
        $table->string('name');                          // Tên voucher
        $table->text('description')->nullable();        // Mô tả

        $table->enum('type', ['percent', 'fixed']);      // Loại giảm giá
        $table->decimal('value', 15, 2);                 // Giá trị (10 = 10% hoặc 50000đ)

        $table->decimal('min_order_amount', 15, 2)->default(0);   // Đơn tối thiểu
        $table->decimal('max_discount', 15, 2)->nullable();       // Giảm tối đa (cho %)

        $table->integer('quantity')->default(0);         // Số lượng
        $table->integer('used_count')->default(0);       // Đã dùng bao nhiêu lần

        $table->dateTime('start_date');                  // Ngày bắt đầu
        $table->dateTime('end_date');                    // Ngày kết thúc

        $table->boolean('is_active')->default(true);     // Bật/tắt
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
