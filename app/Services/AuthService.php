<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthService 
{
    /**
     * Authenticate the user and issue a token.
     *
     * @param string $email
     * @param string $password
     * @return string
     * @throws ValidationException
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken(env('APP_NAME'))->plainTextToken;
        $role = $user->role;

        return ['token' => $token, 'role' => $role];
    }

    /**
     * Logout the user by revoking the token.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function logout($request): void
    {
        $request->user()->currentAccessToken()->delete();
    }

    /**
     * Get the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User
     */
    public function getUser($request): User
    {
        return $request->user();
    }

    /**
     * Update the user's password.
     *
     * @param \App\Models\User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return void
     * @throws ValidationException
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if ($user->id !== Auth::id() && Auth::user()->role !== 'admin') {
            throw ValidationException::withMessages([
                'current_password' => ['You can only update your own password.'],
            ]);
        }

        if ($user->id === Auth::id() && !Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Error updating password.'],
            ]);
        }
                
        $user->password = Hash::make($newPassword);
        $user->save();
    }
    
    
}
