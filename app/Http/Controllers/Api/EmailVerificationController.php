<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{

    public function verify(User $user, $hash)
    {
        if (!hash_equals((string) $user->getKey(), $hash)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid verification link'
            ], 400);
        }
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => false,
                'message' => 'Email already verified'
            ], 400);
        }
        $user->markEmailAsVerified();
        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully',
            'data' => $user
        ], 200);
    }
}
