<?php

namespace App\Contracts;

interface CategoryInterface extends CrawlerInterface
{
    /**
     * Get the category's name.
     *
     * @return null|string
     */
    public function getName(): ?string;

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
     * Get the category's books.
     *
     * @param  \App\Contracts\LanguageInterface  $language
     * @param  int  $page
     *
     * @return array
     */
    public function books(LanguageInterface $language, int $page = 1): array;
}
