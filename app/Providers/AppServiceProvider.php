<?php

namespace App\Providers;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Repositories\BookDetailRepository;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\LanguageRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BookInterface::class, BookRepository::class);
        $this->app->bind(BookDetailInterface::class, BookDetailRepository::class);
        $this->app->bind(CategoryInterface::class, function (Application $app) {
            $category = new CategoryRepository();
            $slugCategory = request()->route()->parameter('category') ?: request()->query('category');

            if ($slugCategory !== null) {
                $category->resolveRouteBinding($slugCategory);
            }

            return $category;
        });
        $this->app->bind(LanguageInterface::class, function (Application $app) {
            $language = new LanguageRepository();
            $slugLanguage = request()->route()->parameter('language') ?: request()->query('language');

            if ($slugLanguage !== null) {
                $language->resolveRouteBinding($slugLanguage);
            }

            return $language;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
