<?php

namespace App\Http\Middleware;

use Sentinel;
use Closure;
use App\Repositories\Auth\BanUserRepository;

class EcomApiAuth
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
        try{
            $user = Sentinel::getUser();
            if($user == null){
                return response()->json(['result'=>false,'title'=>'Attention !','message'=>__('error.account_not_found'),'error'=>'user_not_found'], 400);
            }
            $repo = new BanUserRepository();
            if($repo->isBanned($user)){
                return response()->json(['result'=>false,'title'=>'Banned !','message'=>__('error.account_banned'),'error'=>'user_is_banned'], 400);
            }
        }
        catch(Exception $e){
            if ($e instanceof App\Exceptions\Auth\BannedException) {
                return response()->json(['result'=>false,'title'=>'Banned !','message'=>__('error.account_banned'),'error'=>'user_is_banned'], 400);
            }
            if ($e instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['result'=>false,'title'=>'Attention !','message'=>__('error.login_expired'),'error'=>'token_expired'], $e->getStatusCode());
            }
            if ($e instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['result'=>false,'title'=>'Attention !','message'=>__('error.login_invalid'),'error'=>'token_invalid'], $e->getStatusCode());
            }
        }
        return $next($request);
    }
}
