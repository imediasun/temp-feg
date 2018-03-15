<?php
namespace App\Http\Middleware;

use Closure;

class InputTrim
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        if ($input) {
            array_walk_recursive($input, function (&$item) {
                $item = trim($item);
                //Discovered buggy code in library. It converts all empty strings to null which caused mysql error of column cannot be null
                //freight quote empty shipping notes
                //freehand empty order description
                //$item = ($item == "") ? null : $item;
            });
            $request->merge($input);
        }
        return $next($request);
    }
}