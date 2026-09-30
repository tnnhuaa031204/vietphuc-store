<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Trang danh sách sản phẩm (Trang chủ)
    public function index()
    {
        $categories = Category::all();
        $products = Product::where('is_active', true)->with('category')->latest()->get();

        return view('products.index', compact('products', 'categories'));
    }

    // Trang chi tiết sản phẩm
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();
        
        return view('products.show', compact('product'));
    }
}