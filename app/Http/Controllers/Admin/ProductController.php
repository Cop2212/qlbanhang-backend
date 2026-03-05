<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Cloudinary\Cloudinary;
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
            'thumbnail' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // Tạo cloudinary instance
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_KEY'),
                'api_secret' => env('CLOUDINARY_SECRET'),
            ],
        ]);

        /*
    |-------------------------
    | Upload thumbnail
    |-------------------------
    */

        $thumbnailUrl = null;

        if ($request->hasFile('thumbnail')) {

            $upload = $cloudinary->uploadApi()->upload(
                $request->file('thumbnail')->getRealPath(),
                ['folder' => 'products']
            );

            $thumbnailUrl = $upload['secure_url'];
        }

        /*
    |-------------------------
    | Tạo product
    |-------------------------
    */

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sku' => $request->sku,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'thumbnail' => $thumbnailUrl,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'is_active' => $request->is_active
        ]);

        /*
    |-------------------------
    | Upload multiple images
    |-------------------------
    */

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $image) {

                $upload = $cloudinary->uploadApi()->upload(
                    $image->getRealPath(),
                    ['folder' => 'products']
                );

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $upload['secure_url'],
                    'sort_order' => $key
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công');
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
        //
    }
}
