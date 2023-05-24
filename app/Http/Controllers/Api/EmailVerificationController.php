<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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

    public function sendVerificationEmail(Request $request)
    {

        try {
            /**
             * @var User $user
             */
            $user = $request->user();


            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email already verified'
                ], 400);
            }
            // send welcome email

            retry(2, function () use ($user) {
                Mail::to($user->email)->send(new WelcomeEmail($user));
            });
            // TODO :: mail verification
            // $user->sendWelcomeEmail();
            // Send verification email
            // $user->sendEmailVerificationNotification();

            return response()->json([
                'status' => true,
                'message' => 'Verification email sent successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
