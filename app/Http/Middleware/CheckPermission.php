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

        // dd([
        //     'user_id' => $user?->id,
        //     'email' => $user?->email,
        //     'permission_received' => $permission,
        //     'has_permission' => $user?->hasPermission($permission)
        // ]);

        // check if not user
        if(!$user || !$user->hasPermission($permission)){
            abort(403);
        }

        // otherwise continue
        return $next($request);
    }
}
