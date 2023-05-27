<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavouriteController extends Controller
{
    public function addFavourite(Request $request, Shop $shop)
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

    public function removeFavourite(Request $request, Shop $shop)
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

    public function userFavourites(Request $request)
    {
        $user = $request->user();
        $favourites = $user->favourites()->with('favouritable')->get();
        return response()->json([
            'favourites' => $favourites
        ]);
    }
}
