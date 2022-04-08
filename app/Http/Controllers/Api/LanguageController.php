<?php

namespace App\Http\Controllers\Api;

use App\Contracts\LanguageInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageCollection;
use App\Http\Resources\LanguageResource;

final class LanguageController extends Controller
{
    public function __construct(
        private LanguageInterface $language,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return LanguageCollection
     */
    public function index(): LanguageCollection
    {
        return new LanguageCollection($this->language->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  LanguageInterface  $language
     *
     * @return LanguageResource
     */
    public function show(LanguageInterface $language): LanguageResource
    {
        return new LanguageResource($language);
    }
}
