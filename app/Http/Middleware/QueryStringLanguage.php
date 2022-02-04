<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Illuminate\Http\Request;

final class QueryStringLanguage
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
        $queryStringLanguage = $request->query('language');

        if ($queryStringLanguage !== null) {
            $language = new Language();
            $language->find($queryStringLanguage);

            $request->route()->setParameter('language', $language);
        }

        return $next($request);
    }
}
