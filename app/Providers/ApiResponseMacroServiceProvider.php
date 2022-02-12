<?php

namespace App\Providers;

use App\Support\HttpResponse;
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
        app(ResponseFactory::class)->macro('api', function (array $data = [], int $statusCode = Response::HTTP_OK): Response {
            /** @var ResponseFactory $this */

            $httpResponse = new HttpResponse(
                $statusCode,
                data: ['detail' => $data],
            );

            return new Response(
                content: $httpResponse->toArray(),
                status: $statusCode,
            );
        });

        Response::macro('api', function (): Response {
            /** @var Response $this */

            $httpResponse = new HttpResponse(
                $this->getStatusCode(),
                data: ['detail' => $this->getOriginalContent()],
            );

            $this->setContent($httpResponse->toArray());

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
