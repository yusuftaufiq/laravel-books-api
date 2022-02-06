<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class CacheResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    final public function handle(Request $request, Closure $next)
    {
        return \Cache::remember(
            key: "{$request->method()}:{$request->getUri()}",
            ttl: now()->addSeconds(config('cache.ttl')),
            callback: fn () => $next($request),
        );
    }
}
