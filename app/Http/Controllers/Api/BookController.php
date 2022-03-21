<?php

namespace App\Http\Controllers\Api;

use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

/**
 * @property \App\Models\Book $book
 */
final class BookController extends Controller
{
    final public function __construct(
        private BookInterface $book,
    ) {
    }

    /**
     * Display a listing of the resource.
     * The "$category" and "$language" parameter may contain a value based on the query string.
     *
     * @see \App\Providers\QueryStringCategoryServiceProvider
     * @see \App\Providers\QueryStringLanguageServiceProvider
     *
     * @param Request $request
     * @param CategoryInterface $category
     * @param LanguageInterface $language
     *
     * @return BookCollection
     */
    final public function index(
        Request $request,
        CategoryInterface $category,
        LanguageInterface $language,
    ): BookCollection {
        $page = $request->query('page', 1);
        $books = $this->book->withCategory($category)->withLanguage($language)->all($page);

        return new BookCollection($books);
    }

    /**
     * Display the specified resource.
     *
     * @param BookInterface $book
     *
     * @return BookResource
     */
    final public function show(BookInterface $book): BookResource
    {
        return new BookResource($book);
    }
}
