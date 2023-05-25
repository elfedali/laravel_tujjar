<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Verify the email address using the verification link.
     *
     * @param User   $user The user instance
     * @param string $hash The verification hash
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(User $user, $hash)
    {
        if (!hash_equals((string) $user->getKey(), $hash)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid verification link',
            ], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status'  => false,
                'message' => 'Email already verified',
            ], 422);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'status'  => true,
            'message' => 'Email verified successfully',
            'data'    => $user,
        ], 200);
    }

    /**
     * Send the email verification email.
     *
     * @param Request $request The HTTP request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationEmail(Request $request)
    {
        try {
            /**
             * @var User $user
             */
            $user = $request->user();

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Email already verified',
                ], 422);
            }

            retry(2, function () use ($user) {
                Mail::to($user->email)->send(new WelcomeEmail($user));
            });

            return response()->json([
                'status'  => true,
                'message' => 'Verification email sent successfully',
                'data'    => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
