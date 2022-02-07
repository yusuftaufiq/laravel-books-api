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
     * Get the category by its slug.
     *
     * @param mixed $slug
     *
     * @return static
     */
    public function find(mixed $slug): static;

    /**
     * Get the category's books.
     *
     * @param  \App\Contracts\LanguageInterface  $language
     * @param  int  $page
     *
     * @return array
     */
    public function books(LanguageInterface $language, int $page = 1): array;
}
