<?php

namespace App\Providers;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Contracts\UserInterface;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Category;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        BookInterface::class => Book::class,
        BookDetailInterface::class => BookDetail::class,
        CategoryInterface::class => Category::class,
        LanguageInterface::class => Language::class,
        UserInterface::class => User::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
