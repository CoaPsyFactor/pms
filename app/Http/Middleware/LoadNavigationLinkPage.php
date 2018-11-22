<?php

namespace App\Http\Middleware;

use App\Models\NavigationLink;
use Closure;
use Illuminate\Database\Eloquent\Collection;

class LoadNavigationLinkPage
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
        $slug = $request->route()->parameter('slug');

        /** @var NavigationLink $navigationLink */
        $navigationLink = $request->get('navigation_links')->where('slug', $slug)->first();

        if (false === $navigationLink instanceof NavigationLink) {
            return response()->view('errors.404', ['type' => NavigationLink::class], 404);
        }

        $request->attributes->add(['navigation_link' => $navigationLink]);

        return $next($request);
    }
}
