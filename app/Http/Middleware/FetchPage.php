<?php

namespace App\Http\Middleware;

use App\Models\Page;
use Closure;

class FetchPage
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
        $pageId = $request->get('pageId', $request->route()->parameter('pageId'));

        if (null === $pageId) {
            return response()->view('errors/400', [], 400);
        }

        $page = Page::getActivePageWithId($pageId);

        if (null === $page) {
            return response()->view('errors/404', ['type' => Page::class], 404);
        }

        $request->attributes->add(['page' => $page]);

        return $next($request);
    }
}
