<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CloudinaryService;
use App\Models\Setting;
use App\Models\Slider;
use Cloudinary\Api\Upload\UploadApi;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->paginate(10);

        $maxSlider = Setting::first()->max_sliders ?? 1;

        return view('admin.sliders.index', compact('sliders', 'maxSlider'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'required|image',
            'link' => 'nullable|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'required'
        ]);

        $imageUrl = null;
        $imagePublicId = null;

        if ($request->hasFile('image')) {

            $upload = CloudinaryService::upload(
                $request->file('image'),
                'sliders'
            );

            $imageUrl = $upload['url'];
            $imagePublicId = $upload['public_id'];
        }

        Slider::create([
            'title' => $request->title,
            'image' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'link' => $request->link,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active
        ]);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Thêm slider thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $slider = Slider::findOrFail($id);
        $setting = Setting::first();
        $maxSlider = $setting->max_sliders ?? 0;
        return view('admin.sliders.edit', compact('slider', 'maxSlider'));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image',
            'link' => 'nullable|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'required'
        ]);

        $imageUrl = $slider->image;
        $imagePublicId = $slider->image_public_id;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($slider->image_public_id) {
                CloudinaryService::destroy($slider->image_public_id);
            }

            // Upload ảnh mới
            $upload = CloudinaryService::upload(
                $request->file('image'),
                'sliders'
            );

            $imageUrl = $upload['url'];
            $imagePublicId = $upload['public_id'];
        }

        $slider->update([
            'title' => $request->title,
            'image' => $imageUrl,
            'image_public_id' => $imagePublicId,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'is_active' => $request->is_active
        ]);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Cập nhật slider thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slider = Slider::findOrFail($id);

        // xóa ảnh cloudinary
        CloudinaryService::destroy($slider->image_public_id);

        // xóa DB
        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Xóa slider thành công');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {
            return redirect()
                ->route('admin.sliders.index')
                ->with('error', 'Chưa chọn slider');
        }

        $sliders = Slider::whereIn('id', $ids)->get();

        foreach ($sliders as $slider) {

            // xóa ảnh cloudinary nếu có
            if ($slider->image_public_id) {
                CloudinaryService::destroy($slider->image_public_id);
            }

            $slider->delete();
        }

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Đã xóa các slider đã chọn');
    }

    public function updateMultiple(Request $request)
    {
        $sortOrders = $request->sort_order;
        $isActives = $request->is_active;

        $used = [];

        foreach ($sortOrders as $id => $order) {

            $active = $isActives[$id] ?? 0;

            // chỉ kiểm tra khi slider hiển thị và sort > 0
            if ($active == 1 && $order > 0) {

                if (isset($used[$order])) {

                    return back()->withErrors([
                        'sort_order' => "Có 2 slider đang hiển thị cùng thứ tự {$order}"
                    ]);
                }

                $used[$order] = true;
            }
        }

        // nếu không lỗi thì update
        foreach ($sortOrders as $id => $order) {

            Slider::where('id', $id)->update([
                'sort_order' => $order,
                'is_active' => $isActives[$id] ?? 0
            ]);
        }

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Cập nhật slider thành công');
    }
}
