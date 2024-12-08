<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
    public function login(string $email, string $password): string
    {
        $user = User::where('email', $email)->first();
        Log::info($user);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        return $user->createToken(env('APP_NAME'))->plainTextToken;
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
    public function updatePassword(User $user, string $currentPassword, string $newPassword, bool $isAdmin = false): void
    {
        if (!$isAdmin && !Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password is incorrect.'],
            ]);
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
