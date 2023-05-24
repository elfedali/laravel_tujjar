<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReviewController extends Controller
{



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Shop $shop)
    {
        //$this->authorize('create', [Review::class, $shop]);

        $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review = $shop->reviews()->create([
            'content' => $request->content,
            'rating' => $request->rating,
            'user_id' => auth()->id(),
            'shop_id' => $shop->id,
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
        if ($review->shop_id !== $shop->id) {
            return response()->json([
                'message' => 'You can only edit your own review',
            ], 403);
        }

        $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review->update([
            'content' => $request->content,
            'rating' => $request->rating,
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

        if (auth()->id() !== $review->user_id) {
            return response()->json([
                'message' => 'You can only delete your own review',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}
