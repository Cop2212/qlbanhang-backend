<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\SpecificationTemplate;
use Illuminate\Http\Request;

class ProductSpecificationController extends Controller
{
    public function index(Product $product)
    {
        // Lấy tất cả thông số của sản phẩm
        $specifications = $product->specifications()
            ->orderBy('sort_order')
            ->get();
        $templates = \App\Models\SpecificationTemplate::all();

        return view('admin.products.specifications', compact('product', 'specifications', 'templates'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'template_id' => 'required|exists:specification_templates,id',
            'value'       => 'required|string',
        ]);

        // Kiểm tra sản phẩm đã có thông số này chưa
        $spec = ProductSpecification::where('product_id', $productId)
            ->where('template_id', $request->template_id)
            ->first();

        if ($spec) {
            // UPDATE giá trị
            $spec->update([
                'value' => $request->value,
            ]);
        } else {
            // Tạo mới
            $max = ProductSpecification::where('product_id', $productId)
                ->max('sort_order');

            ProductSpecification::create([
                'product_id'  => $productId,
                'template_id' => $request->template_id,
                'value'       => $request->value,
                'sort_order' => ($max ?? 0) + 1
            ]);
        }

        return back()->with('success', 'Đã lưu thông số!');
    }

    public function destroy($productId, $specId)
    {
        $spec = ProductSpecification::where('product_id', $productId)
            ->where('id', $specId)
            ->firstOrFail();

        $spec->delete();

        return back()->with('success', 'Đã xóa thông số!');
    }

    public function moveUp($productId, $specId)
    {
        $current = ProductSpecification::where('product_id', $productId)
            ->where('id', $specId)
            ->firstOrFail();

        $prev = ProductSpecification::where('product_id', $productId)
            ->where('sort_order', '<', $current->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($prev) {
            $temp = $current->sort_order;
            $current->update(['sort_order' => $prev->sort_order]);
            $prev->update(['sort_order' => $temp]);
        }

        return back();
    }

    public function moveDown($productId, $specId)
    {
        $current = ProductSpecification::where('product_id', $productId)
            ->where('id', $specId)
            ->firstOrFail();

        $next = ProductSpecification::where('product_id', $productId)
            ->where('sort_order', '>', $current->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $temp = $current->sort_order;
            $current->update(['sort_order' => $next->sort_order]);
            $next->update(['sort_order' => $temp]);
        }

        return back();
    }
}
