<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Bắt lỗi Form Đăng Nhập & Điều hướng chuẩn phân quyền
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Vui lòng nhập địa chỉ Email.',
            'email.email'       => 'Địa chỉ Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // ✅ XÓA intended URL cũ để tránh redirect về /admin/dashboard
            $request->session()->forget('url.intended');

            // KIỂM TRA QUYỀN ADMIN ĐỂ ĐIỀU HƯỚNG
            $user = Auth::user();
            if ($user->role === 'admin' || $user->is_admin == 1) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Đăng nhập trang Admin thành công!');
            }

            // ✅ User thường → về trang chủ, KHÔNG dùng intended()
            return redirect()->route('products.index')
                ->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Bắt lỗi Form Đăng Ký
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone'    => 'nullable|string|regex:/^[0-9]{10,11}$/',
        ], [
            'name.required'     => 'Họ và tên không được để trống.',
            'email.required'    => 'Vui lòng nhập Email.',
            'email.email'       => 'Email không đúng định dạng.',
            'email.unique'      => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed'=> 'Xác nhận mật khẩu không khớp.',
            'phone.regex'       => 'Số điện thoại phải từ 10 - 11 chữ số.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone'    => $validated['phone'] ?? null,
            'role'     => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('products.index')
            ->with('success', 'Đăng ký tài khoản thành công!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ✅ Xóa intended URL khi đăng xuất
        $request->session()->forget('url.intended');

        return redirect()->route('login.form');
    }
}