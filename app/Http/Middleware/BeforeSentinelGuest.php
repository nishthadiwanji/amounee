<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class BeforeSentinelGuest
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
            $user = Sentinel::getUser();
            if($user!=null)
            {
                return redirect(route('home'));
            }
            return $next($request);
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
            abort(400, 'Unauthorized action.');
        }
    }
}
