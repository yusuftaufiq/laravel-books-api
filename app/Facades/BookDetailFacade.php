<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BookDetailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bookDetail';
    }
}
