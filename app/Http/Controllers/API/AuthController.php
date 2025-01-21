<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function generateToken(Request $request)
    {
        $payload = $request->json()->all();

        $user = User::where('email', $payload['email'])->first();

        if (! $user || ! Hash::check($payload['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        error_log(json_encode($user));

        $token = $user->createToken('my-app-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function registerUser(Request $request)
    {
        $payload = $request->json()->all();

        $user = User::where('email', $payload['email'])->first();

        if ($user) {
            return response()->json(['message' => 'Email already registered'], 400);
        }

        $user = User::create($payload);

        return response()->json(['status' => 'success'], 201);
    }

    // Method to handle user logout and token revocation
    public function logout(Request $request)
    {
        // Revoke all tokens...
        $request->user()->tokens()->delete();

        // // Revoke the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'You have been successfully logged out.'], 200);
    }
}
