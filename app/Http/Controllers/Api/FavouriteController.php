<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FavouriteController extends Controller
{
    /**
     * Add a shop to favourites.
     *
     * @param Request $request
     * @param Shop $shop
     * @return JsonResponse
     */
    public function addFavourite(Request $request, Shop $shop): JsonResponse
    {
        if ($shop->favourite()) {
            return response()->json([
                'message' => 'Shop added to favourites'
            ]);
        } else {
            return response()->json([
                'message' => 'Shop already in favourites'
            ]);
        }
    }

    /**
     * Remove a shop from favourites.
     *
     * @param Request $request
     * @param Shop $shop
     * @return JsonResponse
     */
    public function removeFavourite(Request $request, Shop $shop): JsonResponse
    {
        if ($shop->unfavourite()) {
            return response()->json([
                'message' => 'Shop removed from favourites'
            ]);
        } else {
            return response()->json([
                'message' => 'Shop not in favourites'
            ]);
        }
    }

    /**
     * Get the user's favourite shops.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userFavourites(Request $request): JsonResponse
    {
        $user = $request->user();
        $favourites = $user->favourites()->with('favouritable')->get();
        return response()->json([
            'favourites' => $favourites
        ]);
    }
}
