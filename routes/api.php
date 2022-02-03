<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;

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

\Route::apiResource('categories', CategoryController::class)->only(['index']);
\Route::apiResource('categories.books', BookController::class)->shallow()->only(['index']);

\Route::apiResource('books', BookController::class)->only(['index', 'show']);

// api/v1/books
// api/v1/books/{book}
// api/v1/books?category={category}&language={language}
// api/v1/categories
// api/v1/categories/{category}/books?language={language}
// api/v1/languages
// api/v1/languages/{language}/books?category={category}
// api/v1/search/{query}?language={language}
