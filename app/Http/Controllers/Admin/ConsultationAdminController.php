<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Product;
use App\Models\Trader;
use App\Models\TraderCommission;

class ConsultationAdminController extends Controller
{
    public function index(Request $request)
    {
        // Validate date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            if ($request->date_from > $request->date_to) {
                return redirect()->route('admin.consultations.index')
                    ->with('error', 'Ngày bắt đầu không thể lớn hơn ngày kết thúc.');
            }
        }

        $query = Consultation::with(['product', 'trader']);
        $products = Product::orderBy('name')->get();
        $traders = Trader::orderBy('name')->get();
        $refCodes = Consultation::select('ref_code')
            ->whereNotNull('ref_code')
            ->distinct()
            ->pluck('ref_code');

        // Filter theo request
        if ($request->filled('phone')) {
            $query->where('phone', $request->phone);
        }
        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        // Filter theo sản phẩm
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter theo affiliate (trader)
        if ($request->filled('trader_id')) {
            $query->where('trader_id', $request->trader_id);
        }

        // Filter theo ref_code
        if ($request->filled('ref_code')) {
            $query->where('ref_code', trim($request->ref_code));
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Lấy danh sách unique phone và email cho filter
        $phones = Consultation::select('phone')->distinct()->orderBy('phone')->pluck('phone');
        $emails = Consultation::select('email')->distinct()->orderBy('email')->pluck('email');

        return view('admin.consultations.index', compact(
            'items',
            'phones',
            'emails',
            'products',
            'traders',
            'refCodes'
        ));
    }

    public function show($id)
    {
        $item = Consultation::findOrFail($id);
        return view('admin.consultations.show', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Consultation::findOrFail($id);
        $item->status = $request->status;
        $item->save();

        return back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function updateAdminMessage(Request $request, $id)
    {
        $item = Consultation::findOrFail($id);

        $item->message_admin = $request->message_admin;
        $item->status = $request->status;

        if ($request->filled('result')) {
            $item->result = $request->result;
        }

        $item->save();

        // ✅ TÍNH HOA HỒNG
        if (
            $item->result === 'bought' &&
            $item->status === 'contacted' &&
            $item->product_id &&
            $item->trader_id &&
            $item->ref_code
        ) {
            $product = Product::find($item->product_id);

            if ($product) {
                $amount = $product->price * 0.2; // 20%

                TraderCommission::firstOrCreate(
                    [
                        'consultation_id' => $item->id,
                    ],
                    [
                        'trader_id' => $item->trader_id,
                        'amount' => $amount,
                        'status' => 'pending',
                    ]
                );
            }
        }

        return back()->with('success', 'Cập nhật ghi chú admin thành công');
    }

    public function destroy($id)
    {
        $item = Consultation::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Xóa tư vấn thành công');
    }
}
