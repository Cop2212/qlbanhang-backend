<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;

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

        $query = Consultation::query();

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

        $items = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Lấy danh sách unique phone và email cho filter
        $phones = Consultation::select('phone')->distinct()->orderBy('phone')->pluck('phone');
        $emails = Consultation::select('email')->distinct()->orderBy('email')->pluck('email');

        return view('admin.consultations.index', compact('items', 'phones', 'emails'));
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
        $item->message_admin = $request->message_admin; // admin thêm/nội dung
        $item->status = $request->status; // admin có thể cập nhật trạng thái
        $item->save();

        return back()->with('success', 'Cập nhật ghi chú admin thành công');
    }

    public function destroy($id)
    {
        $item = Consultation::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Xóa tư vấn thành công');
    }
}
