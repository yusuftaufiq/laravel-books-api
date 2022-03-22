<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookDetailController;
use App\Http\Controllers\Api\BookSearchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\RegisterUserController;
use App\Http\Controllers\Api\UserController;
use Spatie\ResponseCache\Middlewares\CacheResponse;

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

/**
 * @see \App\Providers\AppServiceProvider   for a list of implemented interfaces
 */

\Route::post('/register', RegisterUserController::class)->name('register');

\Route::controller(AuthController::class)->group(function (): void {
    \Route::post('/login', 'login')->name('login');
    \Route::post('/logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

\Route::middleware(['auth:sanctum', CacheResponse::class])->group(function (): void {
    \Route::get('/user', UserController::class)->name('user')->withoutMiddleware(CacheResponse::class);
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
