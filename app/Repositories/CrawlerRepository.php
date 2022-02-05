<?php

namespace App\Repositories;

use App\Contracts\CrawlerInterface;
use App\Traits\RouteBinding;

abstract class CrawlerRepository implements CrawlerInterface
{
    use RouteBinding;

    abstract public function find(string $slug): static;

    abstract public function count(): int;

    abstract public function toArray(): array;
}
