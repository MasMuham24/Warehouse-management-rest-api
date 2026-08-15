<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'viewer',
        ]);

        $user->makeHidden([
            'password',
            'remember_token',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Silakan login.',
            'data' => [
                'user' => $user,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->makeHidden([
            'password',
            'remember_token',
            'role'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();
        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau kadaluwarsa',
            ], 401);
        }
        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        $user->makeHidden([
            'password',
            'remember_token',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diambil.',
            'data' => $user,
        ]);
    }
}
