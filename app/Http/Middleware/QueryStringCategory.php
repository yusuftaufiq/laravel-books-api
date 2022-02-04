<?php

namespace App\Http\Middleware;

use App\Models\Category;
use Illuminate\Http\Request;

final class QueryStringCategory
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
        $queryStringCategory = $request->query('category');

        if ($queryStringCategory !== null) {
            $category = new Category();
            $category->find($queryStringCategory);

            $request->route()->setParameter('category', $category);
        }

        return $next($request);
    }
}
