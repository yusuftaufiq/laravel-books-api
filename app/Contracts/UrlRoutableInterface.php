<?php

namespace App\Contracts;

interface UrlRoutableInterface
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param string $value
     *
     * @return static
     */
    public function resolveRouteBinding(string $value): static;
}
