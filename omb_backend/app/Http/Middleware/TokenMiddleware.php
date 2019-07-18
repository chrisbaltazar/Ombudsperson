<?php

namespace App\Http\Middleware;

use Closure;

class TokenMiddleware
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
        
        if($auth = checkJwtToken($request)){
            
            auth()->loginUsingId($auth->id);
            
            return $next($request);
        }else{
            
            return response()->json([
                "message" => "Error de sesion"
            ], 401);
            
        }
    }
}
