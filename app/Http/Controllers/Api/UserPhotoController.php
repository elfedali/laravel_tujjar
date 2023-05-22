<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserPhotoController extends Controller
{
    public function updatePhoto(Request $request)
    {

        try {
            /**
             * @var User $user
             */
            $user = auth()->user();
            $validateUser = Validator::make($request->all(), [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateUser->errors()->first(),
                ], 400);
            }
            $imageName = $user->id . '_photo' . time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('uploads/users'), $imageName);
            $user->update([
                'photo' => $imageName,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User photo updated successfully',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deletePhoto(Request $request)
    {
        try {
            /**
             * @var User $user
             */
            $user = auth()->user();
            $this->removePhoto($user->photo);
            $user->update([
                'photo' => null,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User photo deleted successfully',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    // delete photo from storage
    private function removePhoto($photoName)
    {
        if ($photoName != null) {
            $photoPath = public_path('uploads/users') . '/' . $photoName;
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }
    }
}
