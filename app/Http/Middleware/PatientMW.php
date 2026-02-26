<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PatientMW
{
    public function handle(Request $request, Closure $next)
    {
        abort_if(!auth()->user()->isPatient(), 403, 'Unauthorized');

        return $next($request);
    }
}
