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
    public function __construct(
        private BookInterface $book,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     *
     * @return \App\Http\Resources\BookCollection
     */
    public function __invoke(Request $request): BookCollection
    {
        $request->validate(['keyword' => ['required']]);

        /** @var string $keyword */
        $keyword = $request->query('keyword');
        $page = (int) $request->query('page', '1');

        $books = $this->book->like($keyword, $page);

        return new BookCollection($books);
    }
}
