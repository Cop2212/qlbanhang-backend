<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{

    // gửi hoặc cập nhật đánh giá
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|max:255',
            'email' => 'required|email',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|max:2000'
        ]);

        $data['ip_address'] = $request->ip();
        $data['is_approved'] = 1;

        // kiểm tra review đã tồn tại chưa
        $review = Review::where('product_id', $data['product_id'])
            ->where('email', $data['email'])
            ->first();

        if ($review) {

            $review->update([
                'name' => $data['name'],
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'ip_address' => $data['ip_address']
            ]);

            return response()->json([
                'message' => 'Đánh giá của bạn đã được cập nhật'
            ]);
        }

        // tạo review mới
        Review::create($data);

        return response()->json([
            'message' => 'Đánh giá đã được gửi'
        ], 201);
    }


    // lấy review theo sản phẩm
    public function productReviews($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->where('is_approved', 1)
            ->latest()
            ->paginate(5);

        $stats = Review::where('product_id', $productId)
            ->where('is_approved', 1)
            ->select(
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as total_reviews')
            )
            ->first();

        $ratingBreakdown = Review::where('product_id', $productId)
            ->where('is_approved', 1)
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating');

        return response()->json([
            'reviews' => $reviews,
            'stats' => [
                'avg_rating' => round($stats->avg_rating, 1),
                'total_reviews' => $stats->total_reviews
            ],
            'breakdown' => $ratingBreakdown
        ]);
    }
}
