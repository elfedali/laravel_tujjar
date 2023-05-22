<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserInfoController extends Controller
{
    public function updateInfo(Request $request)
    {

        try {
            /**
             * @var User $user
             */
            $user = auth()->user();
            $validateUser = Validator::make($request->all(), [
                'first_name' => 'nullable|string',
                'last_name' => 'nullable|string',

                'phone_number' => 'nullable|string',

                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'zip_code' => 'nullable|integer',
                'country' => 'nullable|string',

            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateUser->errors()->first(),
                ], 400);
            }
            $user->update($request->only(
                'first_name',
                'last_name',

                'phone_number',

                'address',
                'city',
                'zip_code',
                'country',

            ));
            return response()->json([
                'status' => true,
                'message' => 'User info updated successfully',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
