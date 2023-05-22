<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserEmailController extends Controller
{

    /**
     * Update the specified resource in storage.
     */
    public function updateEmail(Request $request)
    {
        try {
            /**
             * @var User $user
             */
            $user = auth()->user();
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateUser->errors()->first(),
                ], 400);
            }
            $user->update($request->only('email'));
            $user->markEmailAsUnverified();
            $user->sendEmailVerificationNotification();

            return response()->json([
                'status' => true,
                'message' => 'Email updated successfully',
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
