<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
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
    public function index(Category $category, int $page): Response
    {
        return response($category->books($page), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book): Response
    {
        return response($book, Response::HTTP_OK);
    }
}
