<?php

namespace App\Http\Controllers\Api;

use App\Contracts\BookInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
use Illuminate\Http\Request;

/**
 * @property \App\Models\Book $book
 */
final class BookSearchController extends Controller
{
    final public function __construct(
        private BookInterface $book,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $keyword
     *
     * @return \App\Http\Resources\BookCollection
     */
    final public function __invoke(Request $request, string $keyword): BookCollection
    {
        $page = $request->query('page', 1);
        $books = $this->book->like($keyword, $page);

        return new BookCollection($books);
    }
}
