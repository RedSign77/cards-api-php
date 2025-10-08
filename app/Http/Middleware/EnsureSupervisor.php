<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSupervisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isSupervisor()) {
            return response()->json([
                'message' => 'Unauthorized. Supervisor access required.'
            ], 403);
        }

        return $next($request);
    }
}
