<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TraderCommission;
use App\Models\TraderProfile;

class TraderProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'bank_number' => 'required',
            'bank_owner' => 'required',
            'phone' => 'required',
        ]);

        $trader = Auth::user();

        $profile = TraderProfile::updateOrCreate(
            ['trader_id' => $trader->id],
            [
                'bank_name' => $request->bank_name,
                'bank_number' => $request->bank_number,
                'bank_owner' => $request->bank_owner,
                'phone' => $request->phone,
                'status' => 'pending' // gửi là chờ duyệt
            ]
        );

        return response()->json([
            'message' => 'Cập nhật thành công',
            'profile' => $profile
        ]);
    }

    public function me()
    {
        /** @var \App\Models\Trader $trader */
        $trader = Auth::user();

        $profile = $trader->profile;

        $commissions = $trader->commissions;
        $total = $commissions->sum('amount');
        $paid = $commissions->where('status', 'paid')->sum('amount');
        $pending = $commissions->where('status', 'pending')->sum('amount');

        return response()->json([
            'user' => $trader,
            'profile' => $profile,
            'stats' => [
                'total' => $total,
                'paid' => $paid,
                'pending' => $pending
            ]
        ]);
    }
}
