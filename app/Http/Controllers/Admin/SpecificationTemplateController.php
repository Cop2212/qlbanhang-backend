<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecificationTemplate;
use Illuminate\Http\Request;

class SpecificationTemplateController extends Controller
{
    public function index()
    {
        $templates = SpecificationTemplate::orderBy('id', 'DESC')->paginate(20);
        return view('admin.specifications.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.specifications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:255',
        ]);

        SpecificationTemplate::create($validated);

        return redirect()->route('admin.specifications.index')
            ->with('success', 'Thêm thông số thành công!');
    }

    public function edit(SpecificationTemplate $specification)
    {
        return view('admin.specifications.edit', compact('specification'));
    }

    public function update(Request $request, SpecificationTemplate $specification)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:255',
        ]);

        $specification->update($validated);

        return redirect()->route('admin.specifications.index')
            ->with('success', 'Cập nhật thông số thành công!');
    }

    public function destroy(SpecificationTemplate $specification)
    {
        $specification->delete();

        return redirect()->route('admin.specifications.index')
            ->with('success', 'Xóa thông số thành công!');
    }
}
