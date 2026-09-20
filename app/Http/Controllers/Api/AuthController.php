<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nik' => 'required|string|max:16|unique:users',
            'phone' => 'required|string|max:20',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'nik' => $validated['nik'],
            'phone' => $validated['phone'],
            'is_approved' => false,
        ]);

        $wargaRole = \App\Models\Role::where('name', 'warga')->first();
        if ($wargaRole) {
            $user->roles()->attach($wargaRole->id);
        }

        return response()->json([
            'message' => 'Pendaftaran berhasil. Silakan tunggu verifikasi admin.',
            'user' => $user
        ], 201);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        $user = auth()->guard('api')->user();
        if ($user) {
            $user->load(['roles.permissions.application']);
            
            // Ambil semua permission dari user, ekstrak aplikasi, singkirkan yang null, ambil nama aplikasinya, unik.
            $user->applications = $user->roles->flatMap->permissions->map->application->filter()->pluck('name')->unique()->values()->all();
        }
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = auth()->guard('api')->user();
        if ($user) {
            $user->load(['roles.permissions.application']);
            
            // Ambil semua permission dari user, ekstrak aplikasi, singkirkan yang null, ambil nama aplikasinya, unik.
            $user->applications = $user->roles->flatMap->permissions->map->application->filter()->pluck('name')->unique()->values()->all();
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }
}
