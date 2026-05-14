<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Sai thông tin đăng nhập');
    }

    public function dashboard()
    {
        $totalProducts = \App\Models\Product::count();
        $totalConsultations = \App\Models\Consultation::count();
        $totalTraders = \App\Models\Trader::count();
        $recentConsultations = \App\Models\Consultation::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalConsultations',
            'totalTraders',
            'recentConsultations'
        ));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function changePasswordForm()
    {
        return view('admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác');
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Đổi mật khẩu thành công');
    }
}