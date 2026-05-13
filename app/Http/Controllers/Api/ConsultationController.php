<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Trader;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/'],
            'email' => 'nullable|email',
            'message' => 'nullable|string',
            'product_id' => 'nullable|integer',

            'ref_code' => 'nullable|string',

            'utm_source' => 'nullable|string',
            'utm_medium' => 'nullable|string',
            'utm_campaign' => 'nullable|string',
        ]);

        $data['status'] = 'pending';

        // ✅ resolve trader_id từ ref_code
        if (!empty($data['ref_code'])) {
            $trader = Trader::where('ref_code', $data['ref_code'])->first();

            if ($trader) {
                $data['trader_id'] = $trader->id;
            }
        }

        Consultation::create($data);

        return response()->json([
            'message' => 'Consultation saved successfully'
        ], 201);
    }
}
