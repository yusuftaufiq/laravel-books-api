<?php

namespace App\Contracts;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;

interface UserInterface extends Authorizable, Authenticatable, CanResetPassword
{
}
