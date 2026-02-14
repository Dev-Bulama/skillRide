<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminImpersonate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonate_id')) {
            $originalUser = User::find(session('original_admin_id'));
            if ($originalUser && $originalUser->role === 'admin') {
                view()->share('isImpersonating', true);
                view()->share('originalAdmin', $originalUser);
            } else {
                session()->forget(['impersonate_id', 'original_admin_id']);
            }
        }

        return $next($request);
    }
}
