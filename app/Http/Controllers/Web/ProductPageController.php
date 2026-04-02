<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trader;
use App\Models\TraderLink;
use App\Models\TraderClick;

class ProductPageController extends Controller
{
    public function show(Request $request, $slug)
    {
        $ref = $request->query('ref');
        $linkCode = $request->query('link');

        $link = null;

        if ($linkCode) {
            $link = TraderLink::where('code', $linkCode)->first();
        }

        $productId = $link?->product_id
            ?? \App\Models\Product::where('slug', $slug)->value('id');
        $linkKey = $linkCode ?? 'default';

        $utmSource = $request->query('utm_source');
        $utmMedium = $request->query('utm_medium');
        $utmCampaign = $request->query('utm_campaign');

        if ($ref) {
            $trader = Trader::where('ref_code', $ref)->first();

            if ($trader) {

                // 🔥 lưu session
                session([
                    'trader_id' => $trader->id,
                    'ref_code' => $ref,
                    'trader_link_id' => $link?->id,

                    'utm_source' => $utmSource,
                    'utm_medium' => $utmMedium,
                    'utm_campaign' => $utmCampaign,
                ]);

                // 🔥 chống spam click (rất nên có)
                if (!session('click_logged_' . $linkKey)) {

                    TraderClick::create([
                        'trader_id' => $trader->id,
                        'product_id' => $productId,
                        'trader_link_id' => $link?->id,
                        'ref_code' => $ref,

                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'session_id' => session()->getId(),

                        'utm_source' => $utmSource,
                        'utm_medium' => $utmMedium,
                        'utm_campaign' => $utmCampaign,
                    ]);

                    session(['click_logged_' . $linkKey => true]);
                }
            }
        }

        return view('app');
    }
}
