<?php

namespace App\Providers;

use Goutte\Client;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpClient\HttpClient;

class GoutteServiceProvider extends ServiceProvider
{
    private const HTTP_TIMEOUT = 60;

    /**
     * Register Goutte Facade into service container.
     *
     * @return void
     *
     * @see \App\Facades\GoutteFacade
     */
    public function register()
    {
        $this->app->bind('goutte', fn () => new Client(
            HttpClient::create(['timeout' => self::HTTP_TIMEOUT])
        ));
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
