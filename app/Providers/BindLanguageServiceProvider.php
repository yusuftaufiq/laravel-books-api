<?php

namespace App\Providers;

use App\Contracts\LanguageInterface;
use App\Repositories\LanguageRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BindLanguageServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LanguageInterface::class, function (Application $app) {
            $language = new LanguageRepository();
            $queryStringLanguage = request()->query('language');

            if ($queryStringLanguage !== null) {
                $language->resolveRouteBinding($queryStringLanguage);
            }

            return $language;
        });
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