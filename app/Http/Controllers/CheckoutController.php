<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cod,qr',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'shipping_address' => $request->shipping_address,
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
        ]);

        foreach ($cart as $productId => $item) {
            $order->details()->create([
                'product_id' => $productId,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');

        if ($order->payment_method === 'qr') {
            return redirect()->route('checkout.payment', $order);
        }

        return redirect()->route('checkout.success', $order);
    }

    public function payment(Order $order)
    {
        return view('checkout.payment', compact('order'));
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }
}
