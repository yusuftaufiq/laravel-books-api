<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class CategoryController extends Controller
{
    final public function __construct(
        private Category $category,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function index(): Response
    {
        return response($this->category->all(), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    final public function show(Category $category): Response
    {
        return response($category->toArray(), Response::HTTP_OK);
    }
}
