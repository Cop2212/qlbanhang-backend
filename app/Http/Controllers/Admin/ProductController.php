<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\CloudinaryService;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // tìm theo tên hoặc SKU
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('sku', 'like', '%' . $request->keyword . '%');
            });
        }

        // lọc danh mục
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // lọc thương hiệu
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // số sản phẩm mỗi trang
        $perPage = $request->per_page ?? 10;

        $products = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact(
            'products',
            'categories',
            'brands'
        ));
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        $brands = Brand::where('is_active', 1)->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'sku' => 'required|unique:products',
            'category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'color' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        /*
    |-------------------------
    | Upload thumbnail
    |-------------------------
    */

        $thumbnailUrl = null;
        $thumbnailPublicId = null;

        if ($request->hasFile('thumbnail')) {

            $upload = CloudinaryService::upload(
                $request->file('thumbnail'),
                'products/main'
            );

            $thumbnailUrl = $upload['url'];
            $thumbnailPublicId = $upload['public_id'];
        }

        /*
    |-------------------------
    | Tạo product
    |-------------------------
    */

        $product = Product::create([
            'name' => $request->name,
            'slug' => $this->generateSlug($request->name),
            'sku' => $request->sku,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'thumbnail' => $thumbnailUrl,
            'thumbnail_public_id' => $thumbnailPublicId,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'color' => $request->color,
            'is_active' => $request->is_active
        ]);

        /*
    |-------------------------
    | Upload multiple images
    |-------------------------
    */

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $image) {

                $upload = CloudinaryService::upload(
                    $image,
                    'products/gallery'
                );

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $upload['url'],
                    'image_public_id' => $upload['public_id'],
                    'sort_order' => $key
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }

    private function generateSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
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
        $product = Product::with('images')->findOrFail($id);

        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'brands'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'sku' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $product = Product::findOrFail($id);

        $thumbnailUrl = $product->thumbnail;
        $thumbnailPublicId = $product->thumbnail_public_id;

        /*
|-----------------------
| Xóa thumbnail cũ
|-----------------------
*/

        if ($request->has('delete_thumbnail')) {

            if ($product->thumbnail_public_id) {
                CloudinaryService::destroy($product->thumbnail_public_id);
            }

            $thumbnailUrl = null;
            $thumbnailPublicId = null;
        }

        if ($request->hasFile('thumbnail')) {

            if ($product->thumbnail_public_id) {
                CloudinaryService::destroy($product->thumbnail_public_id);
            }

            $upload = CloudinaryService::upload(
                $request->file('thumbnail'),
                'products/main'
            );

            $thumbnailUrl = $upload['url'];
            $thumbnailPublicId = $upload['public_id'];
        }

        /*
|-----------------------
| Xóa gallery images
|-----------------------
*/

        if ($request->has('delete_images')) {

            $images = ProductImage::whereIn('id', $request->delete_images)->get();

            foreach ($images as $img) {

                if ($img->image_public_id) {
                    CloudinaryService::destroy($img->image_public_id);
                }

                $img->delete();
            }
        }

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $image) {

                $upload = CloudinaryService::upload(
                    $image,
                    'products/gallery'
                );

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $upload['url'],
                    'image_public_id' => $upload['public_id'],
                    'sort_order' => $key
                ]);
            }
        }

        $product->update([
            'name' => $request->name,
            'slug' => $this->generateSlug($request->name),
            'sku' => $request->sku,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'color' => $request->color,
            'thumbnail' => $thumbnailUrl,
            'thumbnail_public_id' => $thumbnailPublicId,
            'is_active' => $request->is_active ?? 0,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        if ($product->thumbnail_public_id) {
            CloudinaryService::destroy($product->thumbnail_public_id);
        }

        // xóa gallery
        foreach ($product->images as $img) {
            if ($img->image_public_id) {
                CloudinaryService::destroy($img->image_public_id);
            }
        }

        // xóa images DB
        $product->images()->delete();

        // xóa product
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Chưa chọn sản phẩm');
        }

        $products = Product::with('images')
            ->whereIn('id', $ids)
            ->get();

        foreach ($products as $product) {

            // Xóa thumbnail Cloudinary
            if ($product->thumbnail_public_id) {
                CloudinaryService::destroy($product->thumbnail_public_id);
            }

            // Xóa gallery Cloudinary
            foreach ($product->images as $img) {
                if ($img->image_public_id) {
                    CloudinaryService::destroy($img->image_public_id);
                }
            }

            // Xóa gallery DB
            $product->images()->delete();

            // Xóa product
            $product->delete();
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã xóa các sản phẩm đã chọn');
    }

    public function updateStatusMultiple(Request $request)
    {
        // trạng thái hiển thị
        if ($request->status) {

            foreach ($request->status as $id => $status) {

                \App\Models\Product::where('id', $id)
                    ->update(['is_active' => $status]);
            }
        }

        // sản phẩm nổi bật
        if ($request->featured) {

            foreach ($request->featured as $id => $featured) {

                \App\Models\Product::where('id', $id)
                    ->update(['is_featured' => $featured]);
            }
        }

        // sản phẩm bán chạy
        if ($request->best_seller) {

            foreach ($request->best_seller as $id => $best) {

                \App\Models\Product::where('id', $id)
                    ->update(['is_best_seller' => $best]);
            }
        }

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái');
    }
}
