<?php

namespace App\Http\Controllers\Api;

use App\Contracts\BookInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;

/**
 * @property \App\Models\Book $book
 */
class SearchController extends Controller
{
    final public function __construct(
        private BookInterface $book,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param string $keyword
     *
     * @return \App\Http\Resources\BookCollection
     */
    public function __invoke(string $keyword): BookCollection
    {
        $page = request()?->query('page', 1);
        $books = $this->book->like($keyword, $page);

        return new BookCollection($books);
    }
}
