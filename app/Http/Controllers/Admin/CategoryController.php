<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Services\CloudinaryService;
use App\Models\ProductImage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->is_active
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Thêm loại thành công!');
    }

    public function deleteMultiple(Request $request)
    {
        if (!$request->ids) {
            return redirect()->back()->with('error', 'Chưa chọn loại');
        }

        $productCount = Product::whereIn('category_id', $request->ids)->count();

        if ($productCount > 0 && !$request->confirm_delete_products) {

            return redirect()->back()->with(
                'confirm_delete_category',
                [
                    'ids' => $request->ids,
                    'count' => $productCount
                ]
            );
        }

        $products = Product::with('images')
            ->whereIn('category_id', $request->ids)
            ->get();

        foreach ($products as $product) {

            // xóa thumbnail Cloudinary
            if ($product->thumbnail_public_id) {
                CloudinaryService::destroy($product->thumbnail_public_id);
            }

            // xóa gallery Cloudinary
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

        Category::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã xóa loại và toàn bộ sản phẩm liên quan');
    }
}
