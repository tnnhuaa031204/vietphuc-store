<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Trang danh sách sản phẩm (Trang chủ & Lọc / Tìm kiếm / Phân trang)
    public function index(Request $request)
    {
        $categories = Category::all();

        // Khởi tạo query lấy các sản phẩm đang active
        $query = Product::where('is_active', true)->with('category');

        // 1. Lọc theo từ khóa tìm kiếm (Tên hoặc Mô tả)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // 2. Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 4. Sắp xếp kết quả
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        // Phân trang 9 sản phẩm/trang và giữ tham số lọc trên URL
        $products = $query->paginate(9)->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    // Trang chi tiết sản phẩm
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'reviews.user'])
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}