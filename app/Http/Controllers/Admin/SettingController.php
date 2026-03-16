<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Services\CloudinaryService;
use App\Models\Company;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $company = Company::first();

        if (!$setting) {
            $setting = Setting::create([]);
        }

        return view('admin.settings.index', compact('setting', 'company'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();

        // validate
        $request->validate([
            'site_name'   => 'nullable|string|max:255',
            'email'       => 'nullable|email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:255',
            'facebook'    => 'nullable|string|max:255',
            'youtube'     => 'nullable|string|max:255',
            'zalo'        => 'nullable|string|max:255',
            'footer_text' => 'nullable|string',
            'max_sliders'  => 'nullable|integer|min:1',
            'logo'        => 'nullable|image|max:2048'
        ]);

        $data = $request->except('logo');

        // upload logo lên Cloudinary
        if ($request->hasFile('logo')) {

            // xóa logo cũ
            CloudinaryService::destroy($setting->logo_public_id);

            // upload logo mới
            $upload = CloudinaryService::upload(
                $request->file('logo'),
                'settings/logo'
            );

            $data['logo'] = $upload['url'];
            $data['logo_public_id'] = $upload['public_id'];
        }

        $setting->update($data);

        return back()->with('success', 'Cập nhật thành công');
    }
}
