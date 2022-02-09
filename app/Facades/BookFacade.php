<?php

namespace App\Facades;

use App\Contracts\BookInterface;
use Illuminate\Support\Facades\Facade;

class BookFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BookInterface::class;
    }
}
