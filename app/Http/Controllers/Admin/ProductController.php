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
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->latest()
            ->paginate(10);

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

        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
}
