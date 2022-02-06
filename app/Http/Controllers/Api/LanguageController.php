<?php

namespace App\Http\Controllers\Api;

use App\Contracts\LanguageInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    final public function index(): Response
    {
        return response($this->language->all(), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contracts\LanguageInterface  $language
     * @return \Illuminate\Http\Response
     */
    final public function show(LanguageInterface $language): Response
    {
        return response($language->toArray(), Response::HTTP_OK);
    }
}
