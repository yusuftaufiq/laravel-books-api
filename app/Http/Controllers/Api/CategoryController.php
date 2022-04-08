<?php

namespace App\Http\Controllers\Api;

use App\Contracts\CategoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;

final class CategoryController extends Controller
{
    public function __construct(
        private CategoryInterface $category,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index(): CategoryCollection
    {
        return new CategoryCollection($this->category->all());
    }

    /**
     * Display the specified resource.
     *
     * @param CategoryInterface $category
     *
     * @return CategoryResource
     */
    public function show(CategoryInterface $category): CategoryResource
    {
        return new CategoryResource($category);
    }
}
