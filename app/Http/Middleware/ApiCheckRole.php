<?php

namespace App\Http\Middleware;

use Sentinel;
use Closure;
use Illuminate\Support\Facades\Route;

class ApiCheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = null)
    {
        $user = Sentinel::getUser();
        $roles = explode('|',$roles);

        foreach($user->roles as $role){
            if(Sentinel::inRole($role->slug)){
                return $next($request);
            }
        }
        return response()->json(['message'=>__('error.unauthorized')], 401);
    }
}
