<?php

namespace App\Http\Controllers\Api;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;

final class BookDetailController extends Controller
{
    final public function __construct(
        private BookDetailInterface $bookDetail,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @param BookInterface $book
     *
     * @return BookResource
     */
    final public function index(BookInterface $book): BookResource
    {
        return new BookResource($book->loadDetail());
    }
}
