<?php

namespace App\Http\Controllers\Api;

use App\Contracts\CategoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class CategoryController extends Controller
{
    final public function __construct(
        private CategoryInterface $category,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function index(): Response
    {
        return response($this->category->all());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function show(CategoryInterface $category): Response
    {
        return response($category);
    }
}
