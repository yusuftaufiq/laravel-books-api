<?php

namespace App\Contracts;

use Symfony\Component\DomCrawler\Crawler;

interface BookInterface extends BaseModelInterface
{
    /**
     * Get the book's crawler.
     *
     * @return null|Crawler
     */
    public function getCrawler(): ?Crawler;

    /**
     * Get all books.
     */
    public function all(
        ?CategoryInterface $category = null,
        ?LanguageInterface $language = null,
        int $page = 1
    ): array;

    /**
     * Get a book by its slug.
     *
     * @param mixed $slug
     *
     * @return static
     */
    public function find(mixed $slug): static;

    /**
     * Get a book details.
     *
     * @return array
     */
    public function details(): array;
}
