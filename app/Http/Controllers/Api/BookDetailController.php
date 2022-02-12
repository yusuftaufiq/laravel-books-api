<?php

namespace App\Http\Controllers\Api;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class BookDetailController extends Controller
{
    final public function __construct(
        private BookDetailInterface $bookDetail,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function index(BookInterface $book): Response
    {
        return response($book->loadDetail())->api();
    }
}
