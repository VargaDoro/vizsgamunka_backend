<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientMW
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // mindig így jobb, mint auth()

        // 1: Ha nincs bejelentkezve → 401 Unauthorized
        if (!$user) {
            return response()->json([
                'message' => 'Bejelentkezés szükséges.'
            ], 401);
        }

        // 2: Ha nem beteg → 403 Forbidden
        if (!$user->isPatient()) {
            return response()->json([
                'message' => 'Ezt a műveletet csak beteg felhasználó végezheti.'
            ], 403);
        }

        return $next($request);
    }
}