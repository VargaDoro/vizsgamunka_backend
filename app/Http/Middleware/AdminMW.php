<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMW
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
         $user = $request->user(); // mindig így jobb, mint auth()

        // 1: Ha nincs bejelentkezve → 401 Unauthorized
        if (!$user) {
            return response()->json([
                'message' => 'Bejelentkezés szükséges.'
            ], 401);
        }

        // 2: Ha nem admin → 403 Forbidden
        if (!$user->isAdmin()) {
            return response()->json([
                'message' => 'Ezt a műveletet csak admin felhasználó végezheti.'
            ], 403);
        }

        return $next($request);
    }
}
