<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Shops retrieved successfully',
            'data' => Shop::all(),
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedShop = Validator::make($request->all(), [
                'owner_id' => 'required|integer|exists:users,id',
                'category_id' => 'required|integer|exists:categories,id',

                'name' => 'required|string|max:255',

                'phone_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'zip_code' => 'nullable|digits:5|integer',
                'country' => 'nullable|string|max:255',

            ]);
            if ($validatedShop->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validatedShop->errors()->first(),
                ], 400);
            }
            $shop = Shop::create($validatedShop->validated());
            return response()->json([
                'status' => true,
                'message' => 'Shop created successfully',
                'data' => $shop,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        return response()->json([
            'status' => true,
            'message' => 'Shop retrieved successfully',
            'data' => $shop,
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {

        try {
            $validatedShop = Validator::make($request->all(), [
                'owner_id' => 'required|integer|exists:users,id',
                'category_id' => 'required|integer|exists:categories,id',

                'name' => 'string|max:255',

                'phone_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'zip_code' => 'nullable|digits:5|integer',
                'country' => 'nullable|string|max:255',
                'logo_photo' => 'nullable|string|max:255',
                'cover_photo' => 'nullable|string|max:255',

            ]);
            if ($validatedShop->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validatedShop->errors()->first(),
                ], 400);
            }
            $shop->update($validatedShop->validated());
            $shop->updateSlug();
            $shop->save();

            return response()->json([
                'status' => true,
                'message' => 'Shop updated successfully',
                'data' => $shop,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {

        $shop->delete();
        return response()->json([
            'status' => true,
            'message' => 'Shop deleted successfully',
        ], 200);
    }
}
