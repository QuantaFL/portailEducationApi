<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Controllers\Middleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthenticateMiddleware extends Middleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$guards
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);
        } catch (TokenExpiredException $e) {
            throw new HttpResponseException(response()->json([
                'error' => 'Token has expired'
            ], 401));
        } catch (TokenInvalidException $e) {
            throw new HttpResponseException(response()->json([
                'error' => 'Token is invalid'
            ], 401));
        } catch (JWTException $e) {
            throw new HttpResponseException(response()->json([
                'error' => 'Token is missing or could not be parsed'
            ], 401));
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json([
                'error' => 'Unauthorized'
            ], 401));
        }

        return $next($request);
    }

    /**
     * Override the redirectTo method to handle API authentication.
     */
    protected function redirectTo($request)
    {
        throw new HttpResponseException(response()->json([
            'error' => 'Unauthorized'
        ], 401));
    }

}
