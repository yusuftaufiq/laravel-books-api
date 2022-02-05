<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Response;

final class BookController extends Controller
{
    final public function __construct(
        private Book $book,
    ) {
        $this->middleware('query.string')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function index(Category $category, Language $language, int $page = 1): Response
    {
        return response($category->books($language, $page), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    final public function show(Book $book): Response
    {
        return response($book->toArray(), Response::HTTP_OK);
    }
}
