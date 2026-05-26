<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {   
        $tenant = app('currentTenant');
        $membership = $tenant->users()->where('user_id', $request->user()->id)->first();
        if(!$membership){
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $userRolse = $membership->pivot->role;
        if(!in_array($userRolse, $roles)){
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
