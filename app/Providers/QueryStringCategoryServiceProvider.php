<?php

namespace App\Providers;

use App\Contracts\CategoryInterface;
use App\Http\Controllers\Api\BookController;
use App\Models\Category;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class QueryStringCategoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $request = request();
        $currentRoute = $request?->route();
        $queryStringCategory = $request?->query('category');

        if (
            $currentRoute?->hasParameter('category') === false
            && $currentRoute?->getController()::class === BookController::class
            && $currentRoute?->getActionMethod() === 'index'
            && $queryStringCategory !== null
        ) {
            $this->app->bind(CategoryInterface::class, fn () => (
                (new Category())->resolveRouteBinding($queryStringCategory)
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
        return [CategoryInterface::class];
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
