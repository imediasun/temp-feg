<?php

namespace App\Http\Middleware;

use App\Models\Core\Groups;
use Closure;

class SximoAuthMiddleware
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
        $superadmin = app('session')->get('gid');
        
        if($superadmin !=Groups::SUPPER_ADMIN)
        {
            return redirect('dashboard')->with('msgstatus','error')->with('messagetext',$superadmin);
        }
        return $next($request);
    }
}
