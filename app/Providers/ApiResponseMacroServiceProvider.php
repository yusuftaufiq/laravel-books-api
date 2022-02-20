<?php

namespace App\Providers;

use App\Support\HttpApiFormat;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class ApiResponseMacroServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureResponseFactoryMacro();
        $this->configureResponseMacro();
    }

    /**
     * Configure the response factory macro.
     *
     * @return void
     */
    protected function configureResponseFactoryMacro(): void
    {
        app(ResponseFactory::class)->macro('api', function (
            array $data = [],
            int $statusCode = Response::HTTP_OK,
        ): Response {
            /** @var ResponseFactory $this */

            $httpApiFormat = new HttpApiFormat(
                $statusCode,
                $data,
            );

            return new Response(
                content: $httpApiFormat->toArray(),
                status: $statusCode,
            );
        });
    }

    /**
     * Configure the response macro.
     *
     * @return void
     */
    protected function configureResponseMacro(): void
    {
        Response::macro('api', function (): Response {
            /** @var Response $this */

            $httpApiFormat = new HttpApiFormat(
                $this->getStatusCode(),
                data: $this->getOriginalContent(),
            );

            $this->setContent($httpApiFormat->toArray());

            return $this;
        });
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return [ResponseFactory::class, Response::class];
    }
}
