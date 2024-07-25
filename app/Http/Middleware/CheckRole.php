<?php

namespace App\Http\Middleware;

use Sentinel;
use Closure;
use Illuminate\Support\Facades\Route;

class CheckRole
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
        if($user==null){
            return redirect('/login');
        }
        $roles = explode('|',$roles);
        if(collect($roles)->contains($user->roles->first()->slug) == false){
            if($request->ajax()){
                return response()->json(['message'=>'unauthorized'],401);
            }
            return redirect('/unauthorized');
        }
        return $next($request);
    }
}
