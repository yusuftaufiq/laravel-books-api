<?php

namespace App\Contracts;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;

interface CrawlerInterface extends Arrayable, \Countable, UrlRoutable
{
    /**
     * Get the collection by its slug.
     *
     * @param string $slug
     *
     * @return static
     */
    public function find(string $slug): static;
}