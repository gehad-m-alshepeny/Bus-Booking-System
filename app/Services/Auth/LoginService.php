<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginService
{
    /**
     * Handle user login and token generation.
     *
     * @param array $credentials
     * @return array
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and the password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials provided.'],
            ]);
        }

        // Generate a new Sanctum token
        $token = $user->createToken('API Token')->plainTextToken;

        // Return the user and token as an associative array
        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    /**
     * Handle user logout (revoke the token).
     *
     * @param $user
     * @return void
     */
    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }
}
