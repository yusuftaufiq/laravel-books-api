<?php

namespace App\Contracts;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

interface UserInterface extends Authorizable, Authenticatable, CanResetPassword, HasApiTokens
{
    /**
     * Check if the user with the given email and password exists.
     *
     * @param string $email
     * @param string $password
     *
     * @return self
     */
    public function authenticate(string $email, string $password): self;

    /**
     * Create a expirable new personal access token for the user.
     *
     * @param string $name
     * @param \DateTime $expiredAt
     * @param array  $abilities
     *
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createExpirableToken(string $name, \DateTime $expiredAt, array $abilities = ['*']): NewAccessToken;
}
