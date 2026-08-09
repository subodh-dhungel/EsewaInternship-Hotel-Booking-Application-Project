<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, String $permission): Response
    {
        // get authenticated user
        $user = $request->user();

        // check if not user
        if(!$user){
            abort(401);
        }

        // check permission deny if they don't have it
        if(!$user->hasPermission($permission)){
            abort(403);
        }

        // otherwise continue
        return $next($request);
    }
}
