<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TraderLink;
use Illuminate\Support\Str;
use App\Models\Product;

class TraderLinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $trader = $request->user();
        if (!$trader) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $existing = TraderLink::where([
            'trader_id' => $trader->id,
            'product_id' => $request->product_id,
        ])->first();

        $product = Product::findOrFail($request->product_id);

        $frontendUrl = config('app.frontend_url');

        if ($existing) {
            return response()->json([
                'link' => $frontendUrl . "/product/{$product->slug}?ref={$trader->ref_code}&link={$existing->code}"
            ]);
        }

        do {
            $code = Str::random(8);
        } while (TraderLink::where('code', $code)->exists());

        TraderLink::create([
            'trader_id' => $trader->id,
            'product_id' => $request->product_id,
            'code' => $code,
            'campaign' => $request->campaign,
            'platform' => $request->platform,
        ]);

        $url = $frontendUrl . "/product/{$product->slug}?ref={$trader->ref_code}&link={$code}";

        return response()->json([
            'link' => $url
        ]);
    }
}
