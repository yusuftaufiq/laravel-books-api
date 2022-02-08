<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BookFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'book';
    }
}
