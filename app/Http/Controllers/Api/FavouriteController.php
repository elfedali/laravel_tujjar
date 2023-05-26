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

        $user = $request->user();
        (new Favourite())->user()->associate($user)->favouritable()->associate($shop)->save();

        return response()->json([
            'message' => 'Shop added to favourites'
        ]);
        /*
        try {

            $user = $request->user();
            $favourite = new Favourite();
            $favourite->user_id = $user->id;
            $favourite->favouritable_id = $shop->id;
            $favourite->favouritable_type = Shop::class;
            $favourite->save();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Shop already in favourites'
            ]);
        }
        return response()->json([
            'message' => 'Shop added to favourites'
        ]);
        */
    }

    public function removeFavourite(Request $request, Shop $shop)
    {
        try {
            $user = $request->user();
            $favourite = $user->favourites()->where('favouritable_id', $shop->id)->first();
            $favourite->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Shop not in favourites'
            ]);
        }
        return response()->json([
            'message' => 'Shop removed from favourites'
        ]);
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
