<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class QueryStringPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, \Closure $next)
    {
        $queryStringPage = $request->query('page', 1);
        $request->route()->setParameter('page', $queryStringPage);

        return $next($request);
    }
}
