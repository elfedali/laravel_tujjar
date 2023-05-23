<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\VerifiesEmails;


class AuthController extends Controller
{

    use VerifiesEmails;

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateUser->errors()->first(),
                ], 400);
            }
            if (!auth()->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials',
                ], 400);
            }
            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateUser->errors()->first(),
                ], 400);
            }
            $user = User::create(array_merge(
                $validateUser->validated(),
                ['password' => bcrypt($request->password)]
            ));

            event(new Registered($user)); // send email verification to user
            $user->refresh(); // refresh user data
            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
