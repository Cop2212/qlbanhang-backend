<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('product')
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá');
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);

        $review->update([
            'is_approved' => !$review->is_approved
        ]);

        return back()->with('success', 'Cập nhật trạng thái thành công');
    }
}
