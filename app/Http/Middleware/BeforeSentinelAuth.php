<?php

namespace App\Http\Middleware;

use Sentinel;
use Closure;
use Route;

class BeforeSentinelAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $uri = $request->path();
            $user = Sentinel::getUser();
            if($user==null)
            {
                if($request->isMethod('get') && !$request->ajax()){
                    session()->put('intended_route',$request->path());
                }
                return redirect('/login');
            }
            else
            {
                request()->request->add(['user'=>$user]);
            }
            return $next($request);
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
            abort(400, 'Unauthorized action.');
        }
    }
}
