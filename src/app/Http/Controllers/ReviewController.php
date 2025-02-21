<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Shop $shop)
    {
        Log::info('Review store method started', [
            'shop_id' => $shop->id,
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);

        try {
            $validated = $request->validated();
            Log::info('Validation passed', [
                'data' => $validated
            ]);

            $reviewData = array_merge($validated, [
                'user_id' => auth()->id(),
                'shop_id' => $shop->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment']
            ]);

            $review = $shop->reviews()->create($reviewData);

            Log::info('Review saved', [
                'review_id' => $review->id
            ]);
            return response()->json([
                'message' => 'レビューを保存しました',
                'review' => $review->load('user'),
                'averageRating' => $shop->averageRating()
            ]);
        } catch (\Exception $e) {
            Log::error('Review creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'レビューの保存に失敗しました'
            ], 500);
        }
    }

    public function destroy(Shop $shop)
    {
        $shop->reviews()->where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'レビューを削除しました',
            'averageRating' => $shop->averageRating()
        ]);
    }

    public function index(Shop $shop)
    {
        $reviews = $shop->reviews()->with('user')->latest()->get();

        $averageRating = $shop->averageRating();

        return view('reviews.index', compact('shop', 'reviews', 'averageRating'));
    }

    public function getUserReview(Shop $shop)
    {
        $review = $shop->reviews()->where('user_id', auth()->id())->first();

        return response()->json([
            'review' => $review
        ]);
    }
}
