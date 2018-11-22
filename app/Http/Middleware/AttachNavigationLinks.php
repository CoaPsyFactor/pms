<?php

namespace App\Http\Middleware;

use App\Models\NavigationLink;
use Closure;

class AttachNavigationLinks
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
        $navigationLinks = NavigationLink::getActiveLinks();

        $request->attributes->add(['navigation_links' => $navigationLinks]);

        return $next($request);
    }
}
