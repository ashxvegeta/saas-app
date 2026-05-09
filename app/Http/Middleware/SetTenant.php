<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID');
        // Check if tenant header exists
        if(!$tenantId) {
           return response()->json(['error' => 'Tenant ID is required'], 400);
        }
        // check if login user belongs to tenant
        $tenant = auth()->user()->tenants()->where('tenant_id', $tenantId)->first();
        if(!tenant){
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        // store globally tenant id for current request
        app()->instance('tenant_id', $tenantId);

        return $next($request);
    }
}
