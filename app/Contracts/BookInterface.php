<?php

namespace App\Contracts;

use Symfony\Component\DomCrawler\Crawler;

interface BookInterface extends CrawlerInterface
{
    /**
     * Get the book's crawler.
     *
     * @return null|Crawler
     */
    public function getCrawler(): ?Crawler;

    /**
     * Get the book's slug.
     *
     * @return null|string
     */
    public function getSlug(): ?string;

    /**
     * Get the book's title.
     *
     * @return null|string
     */
    public function getTitle(): ?string;

    /**
     * Get the book's author.
     *
     * @return null|string
     */
    public function getAuthor(): ?string;

    /**
     * Get the book's price.
     *
     * @return null|float
     */
    public function getPrice(): ?float;


    /**
     * Get the book's image URL.
     *
     * @return null|string
     */
    public function getImageUrl(): ?string;

    /**
     * Get the book's URL.
     *
     * @return null|string
     */
    public function getUrl(): ?string;

    /**
     * Get all books.
     */
    public function all(
        ?CategoryInterface $category = null,
        ?LanguageInterface $language = null,
        int $page = 1
    ): array;

    /**
     * Get the book's details.
     *
     * @return array
     */
    public function details(): array;
}
