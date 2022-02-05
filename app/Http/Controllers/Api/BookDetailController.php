<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class BookDetailController extends Controller
{
    final public function __construct(
        private BookDetail $bookDetail,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function index(Book $book): Response
    {
        return response($book->details(), Response::HTTP_OK);
    }
}
