<?php

namespace App\Repositories;

use App\Contracts\CrawlerInterface;

abstract class CrawlerRepository implements CrawlerInterface
{
    abstract public function find(string $slug): static;

    abstract public function count(): int;

    abstract public function toArray(): array;

    abstract public function getRouteKey(): int|string;

    abstract public function getRouteKeyName(): string;

    public function resolveRouteBinding(mixed $value, $field = null): static
    {
        return $this->find($value);
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception(self::class . ' does not support child bindings.');
    }
}
