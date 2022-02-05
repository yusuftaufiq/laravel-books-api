<?php

namespace App\Http\Controllers\Api;

use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

final class BookController extends Controller
{
    final public function __construct(
        private BookInterface $book,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function index(CategoryInterface $category, LanguageInterface $language): Response {
        return response($category->books($language, request()->query('page', 1)), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function show(BookInterface $book): Response
    {
        return response($book->toArray(), Response::HTTP_OK);
    }
}
