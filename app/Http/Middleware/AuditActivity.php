<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditActivity
{
    public function __construct(protected AuditService $auditService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->auditService->log(
                action: strtolower($request->method()) . ':' . $request->path(),
                user: auth()->user(),
                modelType: null,
                modelId: null,
                oldValues: null,
                newValues: $request->except(['password', 'password_confirmation', '_token']),
                description: $request->method() . ' ' . $request->path()
            );
        }

        return $response;
    }
}
