<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetupLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($request->route()->getParameter('lang', 'en'));

        return $next($request);
    }
}
