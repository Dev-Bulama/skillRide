<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGpsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        // GPS enforcement is handled client-side via JavaScript
        // This middleware ensures GPS-required routes have the flag
        if (auth()->check() && auth()->user()->role === 'rider') {
            view()->share('requireGps', true);
        }

        return $next($request);
    }
}
