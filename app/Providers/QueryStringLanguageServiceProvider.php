<?php

namespace App\Providers;

use App\Contracts\LanguageInterface;
use App\Http\Controllers\Api\BookController;
use App\Models\Language;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class QueryStringLanguageServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Change the query string "?language={slug}" into the language model & register it as a service.
     *
     * @return void
     */
    public function register()
    {
        $request = request();
        $currentRoute = $request->route();
        /** @var string */
        $queryStringLanguage = $request->query('language');

        if (
            $currentRoute?->hasParameter('language') === false
            && $currentRoute->getControllerClass() === BookController::class
            && $currentRoute->getActionMethod() === 'index'
            && $queryStringLanguage !== null
        ) {
            $this->app->bind(LanguageInterface::class, fn () => (
                (new Language())->resolveRouteBinding($queryStringLanguage)
            ));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [LanguageInterface::class];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
