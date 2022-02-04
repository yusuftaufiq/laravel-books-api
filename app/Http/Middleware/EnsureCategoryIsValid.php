<?php

namespace App\Http\Middleware;

use App\Enums\CategoryEnum;
use Illuminate\Http\Request;

final class EnsureCategoryIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    final public function handle(Request $request, \Closure $next)
    {
        $category = $request->query('category');

        if ($category === null || CategoryEnum::tryFrom($category) !== null) {
            return $next($request);
        }
    }
}
