<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;

class ShopCoverController extends Controller
{
    public function uploadCover(Request $request, Shop $shop)
    {
        try {
            $this->authorize('update-shop', $shop);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
        $request->validate([
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->removeCover($shop->cover_photo);

        $coverName = $shop->id . '_cover_' . time() . '.' . $request->cover_photo->extension();

        $request->cover_photo->move(public_path('uploads/shops'), $coverName);

        $shop->cover_photo = $coverName;
        $shop->save();

        return response()->json([
            'status' => true,
            'message' => 'Cover uploaded successfully',
            'data' => $shop,
        ], 200);
    }

    public function deleteCover(Request $request, Shop $shop)
    {
        try {
            $this->authorize('update-shop', $shop);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
        $this->removeCover($shop->cover_photo);
        $shop->cover_photo = null;
        $shop->save();

        return response()->json([
            'status' => true,
            'message' => 'Cover deleted successfully',
            'data' => $shop,
        ], 200);
    }
    // delete cover from storage

    private function removeCover($coverName)
    {
        if ($coverName != null) {
            $coverPath = public_path('uploads/shops') . '/' . $coverName;
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }
    }
}
