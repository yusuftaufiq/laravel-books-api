<?php

namespace App\Contracts;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @property ?string $image
 * @property ?string $title
 * @property ?string $author
 * @property ?string $price
 * @property ?string $url
 * @property ?string $slug
 * @property ?Crawler $crawler
 * @property ?BookDetailInterface $details
 */
interface BookInterface extends BaseModelInterface
{
    /**
     * Create a new book instance.
     *
     * @param ?string $image
     * @param ?string $title
     * @param ?string $author
     * @param ?string $price
     * @param ?string $url
     * @param ?string $slug
     * @param ?Crawler $crawler
     * @param ?BookDetailInterface $details
     *
     * @return void
     */
    public function __construct(
        ?string $image = null,
        ?string $title = null,
        ?string $author = null,
        ?string $price = null,
        ?string $url = null,
        ?string $slug = null,
        ?Crawler $crawler = null,
        ?BookDetailInterface $details = null,
    );

    /**
     * Get all books.
     *
     * @return array<BookInterface>
     */
    public function all(
        ?CategoryInterface $category = null,
        ?LanguageInterface $language = null,
        int $page = 1
    ): array;

    /**
     * Get a book by its slug.
     *
     * @param string $slug
     *
     * @return static
     */
    public function find(string $slug): static;

    /**
     * Load a book detail.
     *
     * @return static
     */
    public function loadDetail(): static;
}
