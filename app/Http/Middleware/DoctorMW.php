<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DoctorMW
{
    public function handle(Request $request, Closure $next)
    {
        abort_if(!auth()->user()->isDoctor(), 403, 'Unauthorized');

        return $next($request);
    }
}
