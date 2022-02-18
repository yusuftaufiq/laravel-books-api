<?php

namespace App\Http\Controllers\Api;

use App\Contracts\LanguageInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageCollection;
use App\Http\Resources\LanguageResource;

final class LanguageController extends Controller
{
    final public function __construct(
        private LanguageInterface $language,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    final public function index(): LanguageCollection
    {
        return new LanguageCollection($this->language->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contracts\LanguageInterface  $language
     * @return \Illuminate\Http\Response
     */
    final public function show(LanguageInterface $language): LanguageResource
    {
        return new LanguageResource($language);
    }
}
