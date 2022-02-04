<?php

namespace App\Models;

abstract class AbstractCrawler implements CrawlerInterface
{
    abstract public function isEmpty(): bool;

    public function resolveRouteBinding($value, $field = null): static
    {
        return tap(new static(), function (self $instance) use ($value) {
            $instance->find($value);
        });
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception(self::class . ' does not support child bindings.');
    }
}
