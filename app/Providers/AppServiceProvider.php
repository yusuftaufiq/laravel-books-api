<?php

namespace App\Providers;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        BookInterface::class => Book::class,
        BookDetailInterface::class => BookDetail::class,
        CategoryInterface::class => Category::class,
        LanguageInterface::class => Language::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('book', fn () => new Book());
        $this->app->bind('bookDetail', fn () => new BookDetail());
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
