<?php

namespace App\Facades;

use App\Contracts\BookDetailInterface;
use Illuminate\Support\Facades\Facade;

class BookDetailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BookDetailInterface::class;
    }
}
