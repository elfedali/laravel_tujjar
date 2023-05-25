<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Shop $shop)
    {

        $validatedData = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review = $shop->reviews()->create([
            'content' => $validatedData['content'],
            'rating' => $validatedData['rating'],
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop, Review $review)
    {
        try {
            $this->authorize('update', $review);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }

        $validatedData = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review->update([
            'content' => $validatedData['content'],
            'rating' => $validatedData['rating'],
        ]);

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop, Review $review)
    {
        try {
            $this->authorize('update', $review);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}
