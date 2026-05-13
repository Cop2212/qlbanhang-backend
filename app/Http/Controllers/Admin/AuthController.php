<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}