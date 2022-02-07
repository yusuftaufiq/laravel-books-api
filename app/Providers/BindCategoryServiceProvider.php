<?php

namespace App\Providers;

use App\Contracts\CategoryInterface;
use App\Models\Category;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BindCategoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryInterface::class, function (Application $app) {
            $category = new Category();
            $queryStringCategory = request()->query('category');

            if ($queryStringCategory !== null) {
                $category->resolveRouteBinding($queryStringCategory);
            }

            return $category;
        });
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
