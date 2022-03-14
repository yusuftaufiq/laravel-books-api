<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookDetailController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\RegisterUserController;

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

\Route::middleware('auth:sanctum')->get('/user', function (\Request $request) {
    return $request->user();
});

\Route::post('/register', RegisterUserController::class)->name('register');
\Route::post('/login', [AuthController::class, 'login'])->name('login');

\Route::middleware('cache.response')->group(function () {
    \Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    \Route::apiResource('categories.books', BookController::class)->shallow()->only(['index']);

    \Route::apiResource('books', BookController::class)->only(['index', 'show']);
    \Route::apiResource('books.details', BookDetailController::class)->shallow()->only(['index']);

    \Route::apiResource('languages', LanguageController::class)->only(['index', 'show']);
    \Route::apiResource('languages.books', BookController::class)->shallow()->only(['index']);

    \Route::get('search/{keyword}', SearchController::class)->name('search');
});
