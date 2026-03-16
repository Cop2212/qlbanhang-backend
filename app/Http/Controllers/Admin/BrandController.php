<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\CloudinaryService;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
            'logo' => 'nullable|image|max:2048'
        ]);

        $logoUrl = null;
        $publicId = null;

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {

            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_KEY'),
                    'api_secret' => env('CLOUDINARY_SECRET'),
                ],
            ]);

            $upload = $cloudinary->uploadApi()->upload(
                $request->file('logo')->getRealPath(),
                ['folder' => 'brands']
            );

            $logoUrl = $upload['secure_url'];
            $publicId = $upload['public_id'];
        }

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'logo' => $logoUrl,
            'logo_public_id' => $publicId,
            'is_active' => $request->is_active ?? 1
        ]);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Thêm hãng thành công!');
    }

    public function deleteMultiple(Request $request)
    {
        if (!$request->ids) {
            return redirect()->back();
        }

        // đếm số sản phẩm
        $productCount = Product::whereIn('brand_id', $request->ids)->count();

        if ($productCount > 0 && !$request->confirm_delete_products) {

            return redirect()->back()->with(
                'confirm_delete_brand',
                [
                    'ids' => $request->ids,
                    'count' => $productCount
                ]
            );
        }

        $brands = Brand::whereIn('id', $request->ids)->get();

        foreach ($brands as $brand) {

            $products = Product::with('images')
                ->where('brand_id', $brand->id)
                ->get();

            foreach ($products as $product) {

                // xóa thumbnail cloudinary
                if ($product->thumbnail_public_id) {
                    CloudinaryService::destroy($product->thumbnail_public_id);
                }

                // xóa gallery cloudinary
                foreach ($product->images as $img) {
                    if ($img->image_public_id) {
                        CloudinaryService::destroy($img->image_public_id);
                    }
                }

                // xóa gallery DB
                $product->images()->delete();

                // xóa product
                $product->delete();
            }

            // xóa logo brand
            if ($brand->logo_public_id) {
                CloudinaryService::destroy($brand->logo_public_id);
            }

            $brand->delete();
        }

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Đã xóa hãng và toàn bộ sản phẩm liên quan');
    }
}
