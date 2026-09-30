<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Lưu đánh giá mới từ khách hàng
     */
    public function store(Request $request, $product)
    {
        // 1. Validate dữ liệu gửi lên từ Form
        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 2. Tìm sản phẩm theo ID hoặc Slug truyền từ route parameter {product}
        $productModel = Product::where('id', $product)
            ->orWhere('slug', $product)
            ->firstOrFail();

        // 3. Tạo bản ghi Đánh giá mới
        Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $productModel->id,
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'] ?? null,
        ]);

        // 4. Quay lại trang chi tiết sản phẩm và thông báo thành công
        return back()->with('success', 'Đánh giá sắc phục của bạn đã được gửi thành công!');
    }
}