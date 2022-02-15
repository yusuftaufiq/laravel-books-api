<?php

namespace App\Http\Controllers\Api;

use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
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
    final public function index(CategoryInterface $category, LanguageInterface $language): BookCollection
    {
        $page = request()?->query('page', 1);
        $books = $this->book->withCategory($category)->withLanguage($language)->paginate($page);

        return new BookCollection($books);
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
