<?php

namespace App\Http\Controllers\Api;

use App\Models\Trader;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TraderAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:traders,email',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).+$/'
            ]
        ]);

        do {
            $refCode = strtoupper(Str::random(8));
        } while (Trader::where('ref_code', $refCode)->exists());

        $trader = Trader::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ref_code' => strtoupper(Str::random(8))
        ]);

        return response()->json([
            'message' => 'Đăng ký thành công',
            'trader' => [
                'id' => $trader->id,
                'name' => $trader->name,
                'email' => $trader->email
            ]
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $trader = Trader::where('email', $request->email)->first();

        if (!$trader || !Hash::check($request->password, $trader->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        // 🔥 Xóa token cũ
        $trader->tokens()->delete();

        // ✅ Access token (ngắn hạn)
        $accessToken = $trader->createToken('access-token')->plainTextToken;

        // ✅ Refresh token (dài hạn)
        $refreshToken = Str::random(64);

        // 🔥 Lưu DB
        $trader->update([
            'refresh_token' => hash('sha256', $refreshToken),
            'refresh_token_expired_at' => now()->addDays(7)
        ]);

        return response()->json([
            'access_token' => $accessToken,
            'trader' => [
                'id' => $trader->id,
                'name' => $trader->name,
                'email' => $trader->email
            ]
        ])->cookie(
            'refresh_token',
            $refreshToken,
            60 * 24 * 7, // 7 ngày
            '/',
            null,
            true,  // ⚠ localhost → false
            true,   // HttpOnly
            false,
            'None'
        );
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()?->delete();

            $user->update([
                'refresh_token' => null,
                'refresh_token_expired_at' => null
            ]);
        }

        return response()
            ->json([
                'message' => 'Đã đăng xuất'
            ])
            ->cookie(
                'refresh_token',
                '',
                -1,
                '/',
                null,
                true,
                true,
                false,
                'None'
            );
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        // 🔥 FIX: check null trước
        if (!$refreshToken) {
            return response()->json([
                'message' => 'No refresh token'
            ], 401);
        }

        $trader = Trader::where(
            'refresh_token',
            hash('sha256', $refreshToken)
        )->first();

        if (!$trader || !$trader->refresh_token_expired_at || now()->gt($trader->refresh_token_expired_at)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $newToken = $trader->createToken('access-token')->plainTextToken;

        return response()->json([
            'access_token' => $newToken
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).+$/',
                'confirmed'
            ],
        ]);

        /** @var \App\Models\Trader $trader */
        $trader = Auth::guard('sanctum')->user();

        if (!Hash::check($request->old_password, $trader->password)) {
            return response()->json(['message' => 'Sai mật khẩu'], 400);
        }

        // 🔥 logout toàn bộ thiết bị
        $trader->tokens()->delete();

        $trader->update([
            'password' => bcrypt($request->password),
            'refresh_token' => null,
            'refresh_token_expired_at' => null
        ]);

        return response()->json([
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}
