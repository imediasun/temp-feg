<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
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
        if (env('APP_ENV', 'development') == 'local') {
            return $next($request);
        }

        if (!$request->secure() && !$request->ajax() ) {
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
