<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GoutteFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'goutte';
    }
}
