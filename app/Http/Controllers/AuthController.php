<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;

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
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->login($request->email, $request->password);
        } catch (\Exception $e) {
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
        $this->authService->logout($request);
        
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

     /**
     * Get the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse | JsonResource
    {

        $user = $this->authService->getUser($request);

        return response()->json(
            [
                'message' => 'User retrieved successfully',
                'status' => 'success',
                'data' => new UserResource($user)
            ],
            Response::HTTP_OK
        );
    }

    public function updatePassword(Request $request, $id)
    {
        $data = $request->input('data');
        $currentPassword = $data['current_password'];
        $newPassword = $data['new_password'];
    
        $userToUpdate = User::findOrFail($id);
        
        $this->authService->updatePassword($userToUpdate, $currentPassword, $newPassword);
        
    }
    
}
