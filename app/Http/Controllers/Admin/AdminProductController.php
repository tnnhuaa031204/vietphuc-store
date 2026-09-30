<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    // 1. Danh sách sản phẩm
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // 2. Form thêm sản phẩm mới
    public function create()
    {
        // Lấy danh sách danh mục để hiển thị ra dropdown (nếu view có select category)
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // 3. Xử lý lưu sản phẩm mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Tự động sinh slug từ tên sản phẩm (Ví dụ: "Áo Mãng Lan" -> "ao-mang-lan")
        $validated['slug'] = Str::slug($request->name);

        // Lưu ảnh nếu có upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // 4. Form chỉnh sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 5. Xử lý cập nhật sản phẩm
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Cập nhật lại slug theo tên mới
        $validated['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // 6. Xóa sản phẩm
    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}