<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;

class ShopLogoController extends Controller
{
    public function uploadLogo(Request $request, Shop $shop)
    {
        try {
            $this->authorize('update-shop', $shop);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }
        $request->validate([
            'logo_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->removeLogo($shop->logo_photo);

        $logoName = $shop->id . '_logo_' . time() . '.' . $request->logo_photo->extension();

        $request->logo_photo->move(public_path('uploads/shops'), $logoName);

        $shop->logo_photo = $logoName;
        $shop->save();

        return response()->json([
            'status' => true,
            'message' => 'Logo uploaded successfully',
            'data' => $shop,
        ], 200);
    }

    public function deleteLogo(Request $request, Shop $shop)
    {
        try {
            $this->authorize('update-shop', $shop);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }

        $this->removeLogo($shop->logo_photo);
        $shop->logo_photo = null;
        $shop->save();

        return response()->json([
            'status' => true,
            'message' => 'Logo deleted successfully',
            'data' => $shop,
        ], 200);
    }

    // delete logo from storage

    private function removeLogo($logoName)
    {
        if ($logoName != null) {
            $logoPath = public_path('uploads/shops') . '/' . $logoName;
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }
        }
    }
}
