<?php

namespace App\Http\Middleware;

use App\Models\Token;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();


        if (!$token || !User::where('bearer_token', $token)->exists()) {
            return response()->json('unauthorized', 401);
        }

        $user = User::where('bearer_token', $token)->first();
        Auth::setUser($user);

        return $next($request);
    }
}
