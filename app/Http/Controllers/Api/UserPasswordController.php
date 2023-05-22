<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserPasswordController extends Controller
{
    public function updatePassword(Request $request)
    {
        try {
            /**
             * @var User $user
             */
            $user = auth()->user();

            $validateUser = Validator::make($request->all(), [
                'password' => 'required|confirmed',
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateUser->errors()->first(),
                ], 400);
            }

            $user->update([
                'password' => bcrypt($request->password)
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Password updated successfully',
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
