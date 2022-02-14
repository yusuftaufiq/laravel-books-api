<?php

namespace App\Http\Controllers\Api;

use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
    final public function index(CategoryInterface $category, LanguageInterface $language): BookCollection
    {
        $page = request()?->query('page', 1);
        $books = $this->book->all($category, $language, $page);

        $paginator = new Paginator($books, 24, $page);
        $paginator->withPath(Book::BASE_URL);

        return new BookCollection($paginator);

        // return new BookCollection($this->book->all($category, $language, request()?->query('page', 1)));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function show(BookInterface $book): Response
    {
        return response($book)->api();
    }
}
