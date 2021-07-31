<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JWTMiddleware
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->wantsJson()) {
            abort('401');
        }
        try {
            if (!$this->auth->parser()->setRequest($request)->hasToken()) {
                return response()->json([
                    'message' => 'TOKEN_NOT_PROVIDED'
                ], 401);
            }
            if (!$this->auth->parseToken()->authenticate()) {
                return response()->json([
                    'message' => 'USER_NOT_FOUND'
                ], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'message' => 'TOKEN_EXPIRED'
            ], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'message' => 'TOKEN_IS_BLACKLISTED'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}