<?php

namespace App\Models;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;

interface CrawlerInterface extends UrlRoutable, Arrayable
{
    /**
     * Get all data from the target resource.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Find data by its slug.
     *
     * @param string $slug
     *
     * @return static
     */
    public function find(string $slug): static;

    /**
     * Get the value of the model's route key.
     *
     * @return string
     */
    public function getRouteKey(): string;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string;

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     *
     * @return static
     */
    public function resolveRouteBinding($value, $field = null): static;

    /**
     * Retrieve the child model for a bound value.
     *
     * @param  string  $childType
     * @param  mixed  $value
     * @param  string|null  $field
     *
     * @throws \Exception
     */
    public function resolveChildRouteBinding($childType, $value, $field): void;
}
