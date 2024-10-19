<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * Handle login request and generate token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Call the login service with validated data
            $loginData = $this->loginService->login($request->validated());

            // Return a structured response using LoginResource
            return response()->json(new LoginResource((object) $loginData), 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

   /**
     * Logout user (Revoke token)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}


