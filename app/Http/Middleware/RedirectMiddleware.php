<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if table exists to avoid crashing if migrations haven't run yet
        if (!Schema::hasTable('redirects')) {
            return $next($request);
        }

        // Get the current path (stripped of domain and query params)
        $path = $request->getPathInfo();
        
        // Look for a redirect match
        $redirect = \App\Models\Redirect::where('source_url', $path)
            ->orWhere('source_url', rtrim($path, '/'))
            ->first();

        if ($redirect) {
            return redirect($redirect->target_url, $redirect->status_code);
        }

        return $next($request);
    }
}
