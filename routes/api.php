<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookDetailController;
use App\Http\Controllers\Api\BookSearchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\RegisterUserController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// phpcs:ignore
/**
 * For a list of implementations of interfaces into concrete
 * classes, see $bindings in \App\Providers\AppServiceProvider.
 *
 * @see \App\Providers\AppServiceProvider
 */

\Route::post('/register', RegisterUserController::class)->name('register');

\Route::controller(AuthController::class)->group(function (): void {
    \Route::post('/login', 'login')->name('login');
    \Route::post('/logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

/**
 * Middleware auth:sanctum, cache.headers & cache.response
 * refers to $routeMiddleware in \App\Http\Kernel. Please
 * see here for more details.
 *
 * @see \App\Http\Kernel
 */
\Route::middleware('auth:sanctum')->group(function (): void {
    \Route::get('/user', UserController::class)->name('user');

    \Route::middleware([
        /** @phpstan-ignore-next-line */
        sprintf('cache.headers:public;max_age=%d;etag', (int) config('responsecache.cache_lifetime_in_seconds')),
        'cache.response',
    ])->group(function (): void {
        \Route::get('search/{keyword}', BookSearchController::class)->name('books.search');

        /**
         * All classes that extend \App\Models\BaseModel have their own
         * route model binding implementation, see here for more details.
         *
         * @see \App\Models\BaseModel
         */
        \Route::apiResource('books', BookController::class)->only(['index', 'show']);
        \Route::apiResource('books.detail', BookDetailController::class)->shallow()->only(['index']);

        \Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
        \Route::apiResource('categories.books', BookController::class)->shallow()->only(['index']);

        \Route::apiResource('languages', LanguageController::class)->only(['index', 'show']);
        \Route::apiResource('languages.books', BookController::class)->shallow()->only(['index']);
    });
});
