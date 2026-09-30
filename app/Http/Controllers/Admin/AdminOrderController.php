<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10); 
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', '!=', 'admin')->count();

        // Trỏ chính xác đến resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', compact('orders', 'totalOrders', 'totalProducts', 'totalCustomers')); 
    }

    public function show(Order $order)
    {
        $order->load(['user', 'details.product']); 
        return view('admin.orders.show', compact('order')); 
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,shipping,completed,cancelled', 
        ]);

        $order->update([
            'order_status' => $validated['order_status'], 
        ]);

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!'); 
    }
}