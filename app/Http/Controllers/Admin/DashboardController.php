<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
    $totalOrders = Order::count();
    $totalProducts = Product::count();
    $totalCustomers = User::where('role', 'customer')->count();
    $recentOrders = Order::latest()->take(5)->get();

    // ✅ Nếu view dùng $orders, đổi thành $orders
    $orders = $recentOrders;

    return view('admin.dashboard', compact('orders', 'totalOrders', 'totalProducts', 'totalCustomers'));
    }
}