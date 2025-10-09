<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\Recaptcha;

class SupervisorController extends Controller
{
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
            'g-recaptcha-response' => ['required', new Recaptcha()],
        ]);

        $user = User::where('email', $request->email)
            ->where('supervisor', true)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Supervisor login successful',
            'token' => $token,
            'user' => $user,
        ], 200);
    }
}
