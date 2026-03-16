<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::where('is_active', 1)
            ->select('id', 'name', 'slug', 'price', 'thumbnail', 'color')
            ->get();
    }

    public function show($slug)
    {
        $product = Product::with(['images', 'category', 'brand'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $similar = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', 1)
            ->select('id', 'name', 'price', 'thumbnail', 'slug')
            ->limit(4)
            ->get();

        return [
            'product' => $product,
            'similar_products' => $similar
        ];
    }

    public function featuredProducts()
    {
        return Product::where('is_featured', 1)
            ->where('is_active', 1)
            ->select('id', 'name', 'slug', 'price', 'thumbnail', 'color')
            ->latest()
            ->take(8)
            ->get();
    }
}
