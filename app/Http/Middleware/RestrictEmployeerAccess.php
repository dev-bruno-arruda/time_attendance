<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RestrictEmployeerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        $employeerId = $request->route('id');
        if ($user && $user->role === 'employee') {
            if ($user->employeer && $user->employeer->id == $employeerId) {
                return $next($request);
            }

            return response()->json([
                'message' => 'Access denied: You can only view your own data.',
                'status' => 'error'
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'message' => 'Access denied.',
            'status' => 'error'
        ], Response::HTTP_FORBIDDEN);
    }
}
