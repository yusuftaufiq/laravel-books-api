<?php

namespace App\Traits;

trait RouteBinding
{
    public function resolveRouteBinding(string $value): static
    {
        $this->find($value);

        return $this;
    }
}
