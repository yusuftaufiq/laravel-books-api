<?php

namespace App\Http\Middleware;

use App\Enums\LanguageEnum;
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
        $language = $request->query('language');

        if ($language === null || LanguageEnum::tryFrom($language) !== null) {
            return $next($request);
        }
    }
}
