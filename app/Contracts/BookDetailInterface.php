<?php

namespace App\Contracts;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @property ?string $releaseDate
 * @property ?string $description
 * @property ?string $language
 * @property ?string $country
 * @property ?string $publisher
 * @property ?int $pageCount
 * @property ?string $category
 * @property ?string $slug
 * @property ?Crawler $crawler
 */
interface BookDetailInterface extends BaseModelInterface
{
    /**
     * Create a new book detail instance.
     *
     * @param ?string $releaseDate
     * @param ?string $description
     * @param ?string $language
     * @param ?string $country
     * @param ?string $publisher
     * @param ?int $pageCount
     * @param ?string $category
     * @param ?string $slug
     * @param ?Crawler $crawler
     *
     * @return void
     */
    public function __construct(
        ?string $releaseDate = null,
        ?string $description = null,
        ?string $language = null,
        ?string $country = null,
        ?string $publisher = null,
        ?int $pageCount = null,
        ?string $category = null,
        ?string $slug = null,
        ?Crawler $crawler = null,
    );

    /**
     * Get a book detail by its slug.
     *
     * @param string $slug
     *
     * @return self
     */
    public function find(string $slug): self;
}
