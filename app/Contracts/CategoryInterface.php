<?php

namespace App\Contracts;

interface CategoryInterface extends BaseModelInterface
{
    /**
     * Get the category's slug.
     *
     * @return null|string
     */
    public function getSlug(): ?string;

    /**
     * Get all categories.
     */
    public function all(): array;

    /**
     * Get a category by its slug.
     *
     * @param string $slug
     *
     * @return static
     */
    public function find(string $slug): static;
}
