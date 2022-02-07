<?php

namespace App\Contracts;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;

interface BaseModelInterface extends Arrayable, \Countable, UrlRoutable
{
}
