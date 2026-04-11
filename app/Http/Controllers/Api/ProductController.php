<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Trader;
use App\Models\TraderLink;
use App\Models\TraderClick;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', 1);
        $perPage = $request->per_page ?? 8;

        // FILTER CATEGORY
        if ($request->category_id) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        // SORT
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            case 'best_seller':
                $query->where('is_best_seller', 1)
                    ->latest();
                break;

            case 'featured':
                $query->where('is_featured', 1)
                    ->latest();
                break;

            default:
                $query->latest();
                break;
        }

        return $query
            ->select('id', 'name', 'slug', 'price', 'thumbnail', 'color')
            ->paginate($perPage);
    }

    public function show(Request $request, $slug)
    {
        // 🔥 LOAD PRODUCT (BẮT BUỘC)
        $product = Product::with([
            'images',
            'categories',
            'brand',
            'specifications' => function ($q) {
                $q->orderBy('sort_order')->with('template');
            }
        ])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $categoryIds = $product->categories->pluck('id');

        $similar = Product::whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        })
            ->where('id', '!=', $product->id)
            ->where('is_active', 1)
            ->select('id', 'name', 'price', 'thumbnail', 'slug')
            ->limit(8)
            ->get();

        $product->specifications = $product->specifications->map(function ($spec) {
            return [
                'name' => $spec->template->name,
                'value' => $spec->value,
                'sort_order' => $spec->sort_order
            ];
        });

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
            ->take(100)
            ->get();
    }

    public function bestSeller()
    {
        return Product::where('is_best_seller', 1)
            ->where('is_active', 1)
            ->select('id', 'name', 'slug', 'price', 'thumbnail')
            ->latest()
            ->take(8)
            ->get();
    }
}
