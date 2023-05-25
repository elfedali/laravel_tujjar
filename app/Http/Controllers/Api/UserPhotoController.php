<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserPhotoController extends Controller
{
    /**
     * Update the user's photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhoto(Request $request)
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $photo = $request->file('photo');
            $imageName = $user->id . '_photo' . time() . '.' . $photo->getClientOriginalExtension();

            // Store the photo in the storage disk
            $photo->storeAs('uploads/users', $imageName, 'public');

            // Delete the previous photo if it exists
            $this->removePhoto($user->photo);

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

    /**
     * Delete the user's photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePhoto(Request $request)
    {
        try {
            $user = $request->user();

            // Delete the current photo if it exists
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

    /**
     * Remove the photo from storage.
     *
     * @param  string|null  $photoName
     * @return void
     */
    private function removePhoto(?string $photoName)
    {
        if ($photoName !== null) {
            Storage::disk('public')->delete('uploads/users/' . $photoName);
        }
    }
}
