<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Services\CloudinaryService;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;

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
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        if (!$request->ids) {
            return redirect()->back()->with('error', 'Chưa chọn loại');
        }

        // Đếm sản phẩm thuộc category
        $productCount = DB::table('category_product')
            ->whereIn('category_id', $request->ids)
            ->count();

        // Nếu có sản phẩm thì hỏi xác nhận
        if ($productCount > 0 && !$request->confirm_delete_products) {
            return redirect()->back()->with(
                'confirm_delete_category',
                [
                    'ids' => $request->ids,
                    'count' => $productCount
                ]
            );
        }

        // 🔥 QUAN TRỌNG: chỉ bỏ liên kết, KHÔNG xóa product
        DB::table('category_product')
            ->whereIn('category_id', $request->ids)
            ->delete();

        // Xóa category
        Category::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã xóa loại (sản phẩm vẫn được giữ lại)');
    }
}
