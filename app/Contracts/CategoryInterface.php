<?php

namespace App\Contracts;


/**
 * @property ?string $slug
 * @property ?string $name
 */
interface CategoryInterface extends BaseModelInterface
{
    /**
     * Create a new category instance.
     *
     * @param ?string $slug
     * @param ?string $name
     *
     * @return void
     */
    public function __construct(?string $slug = null, ?string $name = null);

    /**
     * Get all categories.
     *
     * @return array<CategoryInterface>
     */
    public function all(): array;

    /**
     * Get a category by its slug.
     *
     * @param string $slug
     *
     * @return self
     */
    public function find(string $slug): self;
}
