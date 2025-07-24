<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:12',
        ]);

        User::create($request->all());

        return response()->json(['message' => 'User registered successfully.'], 201);
    }

    /**
     * Login the user and return a token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        if (!empty($user)) {
            if (Hash::check($request->input('password'), $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                    'user' => $user,
                ], 200);
            }

            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        return response()->json(['message' => 'Email is invalid.'], 404);
    }

    /**
     * Get the authenticated user's profile.
     */
    public function profile()
    {
        $userData = auth()->user();
        if (!$userData) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user' => $userData,
        ], 200);
    }

    /**
     * Logging out the authenticated user.
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
