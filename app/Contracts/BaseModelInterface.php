<?php

namespace App\Contracts;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface BaseModelInterface extends Arrayable, \Countable, Jsonable, \JsonSerializable, UrlRoutable
{
}
