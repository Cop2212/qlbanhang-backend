<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trader;
use Illuminate\Http\Request;
use App\Models\Consultation;

class TraderController extends Controller
{
    public function index(Request $request)
    {
        $query = Trader::with('profile')->withCount('consultations');

        // Filter email
        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }

        // Filter ref_code
        if ($request->filled('ref_code')) {
            $query->where('ref_code', $request->ref_code);
        }

        $traders = $query->latest()->paginate(10)->withQueryString();

        // Data cho dropdown
        $emails = Trader::select('email')->distinct()->orderBy('email')->pluck('email');

        $refCodes = Trader::select('ref_code')->distinct()->orderBy('ref_code')->pluck('ref_code');

        return view('admin.traders.index', compact(
            'traders',
            'emails',
            'refCodes'
        ));
    }

    public function approve($id)
    {
        $trader = Trader::findOrFail($id);

        if ($trader->profile) {
            $trader->profile->update([
                'status' => 'approved'
            ]);
        }

        return back()->with('success', 'Đã duyệt trader');
    }

    public function reject($id)
    {
        $trader = Trader::findOrFail($id);

        if ($trader->profile) {
            $trader->profile->update([
                'status' => 'rejected'
            ]);
        }

        return back()->with('success', 'Đã từ chối trader');
    }

    public function show($id)
    {
        $trader = \App\Models\Trader::with('profile')->findOrFail($id);

        return view('admin.traders.show', compact('trader'));
    }
}
