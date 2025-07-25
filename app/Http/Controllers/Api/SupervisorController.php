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
        ]);

        $user = User::where('email', $request->email)
            ->where('supervisor', true)
            ->firstOrFail();

        if (!empty($user)) {
            if (Hash::check($request->input('password'), $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Supervisor login successful',
                    'token' => $token,
                    'user' => $user,
                ], 200);
            }

            return response()->json(['message' => 'Supervisor invalid credentials.'], 401);
        }

        return response()->json(['message' => 'Supervisor email is invalid.'], 404);
    }
}
