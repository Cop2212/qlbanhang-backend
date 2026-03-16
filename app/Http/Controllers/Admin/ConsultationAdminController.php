<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationAdminController extends Controller
{
    public function index()
    {
        $items = Consultation::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.consultations.index', compact('items'));
    }

    public function show($id)
    {
        $item = Consultation::findOrFail($id);
        return view('admin.consultations.show', compact('item'));
    }

    public function updateStatus(Request $request, $id)
    {
        $item = Consultation::findOrFail($id);
        $item->status = $request->status;
        $item->save();

        return back()->with('success', 'Cập nhật trạng thái thành công');
    }
}
