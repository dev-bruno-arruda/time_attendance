<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login the user and return the token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->login($request->email, $request->password);
        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Login fail',
                'status' => 'error'
            ], 422);
        }
        return response()->json(['token' => $token], 200);
    }

    /**
     * Logout the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try{
            $this->authService->logout($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout fail',
                'status' => 'error'
            ], 422);
        }
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
