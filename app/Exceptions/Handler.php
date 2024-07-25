<?php

namespace App\Exceptions;

use Exception;
use Sentinel;
use Session;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\Auth\BannedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof BannedException) {
            return $this->manageBannedException($request);
        }
        if ($exception instanceof TokenMismatchException) {
            if($request->ajax()){
                return response()->json(array('result'=>false, 'message'=> "Please refresh to continue." ,'title'=>"Attention !"), 400);
            }
        }
        if ($exception instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response()->json(['result'=>false,'title'=>'Attention !','message'=>'You\'re login is expired. Please login again.','error'=>'token_expired'], $exception->getStatusCode());
        }
        if ($exception instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            return response()->json(['result'=>false,'title'=>'Attention !','message'=>'You\'re login is invalid. Please login again.','error'=>'token_invalid'], $exception->getStatusCode());
        }
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest(route('auth.login'));
    }

    protected function manageBannedException($request){
        Sentinel::disableCheckpoints();
        if(Sentinel::getUser()){
            Sentinel::getUser()->logoutFromSystem();
        }
        Sentinel::enableCheckpoints();
        if($request->ajax() || $request->is('api/*')){
            return response()->json(['result'=>false,'title'=>'Banned !','message'=>'You\'re account has been banned in the system.','error'=>'user_is_banned'], 400);
        }
        return redirect()->guest(route('errors.403'));
    }
}
