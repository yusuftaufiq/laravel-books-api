<?php

namespace App\Http\Middleware;

use App\Models\Category;
use Illuminate\Http\Request;

final class QueryStringCategory
{
    public function __construct(
        private Category $category,
    ) {
    }

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
            $this->category->find($queryStringCategory);

            $request->route()->setParameter('category', $this->category);
        }

        return $next($request);
    }
}
