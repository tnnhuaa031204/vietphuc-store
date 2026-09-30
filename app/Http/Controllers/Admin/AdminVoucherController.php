<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class AdminVoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'             => 'required|string|max:50|unique:vouchers,code',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'type'             => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'max_discount'     => 'nullable|numeric|min:0',
            'quantity'         => 'required|integer|min:0',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Tạo voucher thành công!');
    }

    public function show(Voucher $voucher)
    {
        return view('admin.vouchers.show', compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code'             => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'type'             => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'max_discount'     => 'nullable|numeric|min:0',
            'quantity'         => 'required|integer|min:0',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Cập nhật voucher thành công!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Đã xóa voucher.');
    }
}