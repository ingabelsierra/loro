<?php

namespace App\Http\Middleware;

use Closure;

class ApiMind
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
        $headerss = $request->headers->all();
        $username = $headerss['authorization'][0];
        if($username == env('Mind')){
            return $next($request);
        }
        return("acceso no permitido");
    }
}
