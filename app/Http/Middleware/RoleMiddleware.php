<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->status !== 'active' && $user->role !== 'admin') {
            if ($user->status === 'pending') {
                auth()->logout();
                return redirect()->route('login')->with('info', 'Your account is under review. Approval typically takes up to 24 hours. You will be notified once your account is activated.');
            }

            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended. Please contact support for assistance.');
        }

        return $next($request);
    }
}
