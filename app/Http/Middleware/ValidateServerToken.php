<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateServerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $siteId = $request->header('X-Site-Id');
        $serverToken = $request->header('X-Server-Token');

        if (! $siteId || ! $serverToken) {
            return response()->json([
                'error' => 'Missing required headers: X-Site-Id and X-Server-Token',
            ], 401);
        }

        $site = Site::where('id', $siteId)
            ->where('server_token', $serverToken)
            ->first();

        if (! $site) {
            return response()->json([
                'error' => 'Invalid site credentials',
            ], 401);
        }

        // Add the site to the request for use in the controller
        $request->merge(['validated_site' => $site]);

        return $next($request);
    }
}
