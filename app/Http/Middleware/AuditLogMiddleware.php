<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            if (!$request->is('build/*') && !$request->is('storage/*')) {
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action'  => $request->route()?->getName() ?? $request->path(),
                    'ip'      => $request->ip(),
                    'user_agent' => substr((string)$request->userAgent(), 0, 255),
                    'context' => [
                        'method' => $request->method(),
                        'query'  => $request->query(),
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            // swallow errors
        }

        return $response;
    }
}

