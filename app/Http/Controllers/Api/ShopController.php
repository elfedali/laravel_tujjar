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
        // Display all shops with their categories and tags in a paginated way with pagination.per_page shops per page. 
        // The response should be a JSON object with the following structure:
        $shops = Shop::with('categories', 'tags', 'owner')->paginate(config('app.pagination.per_page'));
        return response()->json([
            'status' => true,
            'message' => 'Shops retrieved successfully',
            'data' => $shops,
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

                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',

                'phone_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'zip_code' => 'nullable|digits:5|integer',
                'country' => 'nullable|string|max:255',

                'categories' => 'required|array',
                'categories.*' => 'required|integer|exists:categories,id',

                'tags' => 'array',
                'tags.*' => 'integer|exists:tags,id',

                "images" => "array|max:5",
                "images.*" => "file|mimes:jpeg,png,jpg,gif|max:2048",
            ]);
            if ($validatedShop->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validatedShop->errors()->first(),
                ], 400);
            }
            $shop = Shop::create($validatedShop->validated());
            // attach categories
            $shop->categories()->attach($validatedShop->validated()['categories']);

            if (isset($validatedShop->validated()['tags'])) {
                $shop->tags()->attach($validatedShop->validated()['tags']);
            } else {
                $shop->tags()->attach([]);
            }
            if (isset($validatedShop->validated()['images'])) {
                foreach ($validatedShop->validated()['images'] as $image) {
                    $extension = $image->extension();
                    // Generate a unique name using hashing
                    $hashedName = hash_file('md5', $image->path()) . '.' . $extension;
                    $image->move(public_path('uploads/shops'), $hashedName);

                    $shop->images()->create([
                        'name' => $hashedName,
                    ]);
                }
            }

            $shop->load('categories');
            $shop->load('tags');
            $shop->load('images');

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
        $shop->load('categories');
        $shop->load('tags');
        $shop->load('images');
        $shop->load('owner');
        $shop->load('reviews');

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

                'name' => 'string|max:255',
                'description' => 'nullable|string|max:255',

                'phone_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'zip_code' => 'nullable|digits:5|integer',
                'country' => 'nullable|string|max:255',
                'logo_photo' => 'nullable|string|max:255',
                'cover_photo' => 'nullable|string|max:255',

                'categories' => 'array',
                'categories.*' => 'integer|exists:categories,id',

                'tags' => 'array',
                'tags.*' => 'integer|exists:tags,id',

                "images" => "array|max:5",
                "images.*" => "file|mimes:jpeg,png,jpg,gif|max:2048",

            ]);
            if ($validatedShop->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validatedShop->errors()->first(),
                ], 400);
            }
            $shop->update($validatedShop->validated());
            $shop->updateSlug();

            // attach categories
            if (isset($validatedShop->validated()['categories'])) {
                $shop->categories()->sync($validatedShop->validated()['categories']);
            } else {
                $shop->categories()->sync([]);
            }

            if (isset($validatedShop->validated()['tags'])) {
                $shop->tags()->sync($validatedShop->validated()['tags']);
            } else {
                $shop->tags()->sync([]);
            }
            if (isset($validatedShop->validated()['images'])) {
                // delete old images

                foreach ($shop->images as $image) {
                    $this->removePhoto($image->name);
                    $image->delete();
                }
                foreach ($validatedShop->validated()['images'] as $image) {
                    $extension = $image->extension();

                    $hashedName = hash_file('md5', $image->path()) . '.' . $extension;
                    $image->move(public_path('uploads/shops'), $hashedName);

                    $shop->images()->create([
                        'name' => $hashedName,
                    ]);
                }
            }


            $shop->save();

            $shop->load('categories');
            $shop->load('tags');
            $shop->load('images');

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

    // delete photo from storage
    private function removePhoto($photoName)
    {
        try {
            if ($photoName != null) {
                $photoPath = public_path('uploads/shops') . '/' . $photoName;
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
