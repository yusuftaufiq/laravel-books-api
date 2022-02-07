<?php

namespace App\Providers;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Models\Book;
use App\Models\BookDetail;
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
