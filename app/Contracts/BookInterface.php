<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property ?string $image
 * @property ?string $title
 * @property ?string $author
 * @property ?string $price
 * @property ?string $originalUrl
 * @property ?string $url
 * @property ?string $slug
 * @property ?Crawler $crawler
 * @property ?BookDetailInterface $detail
 * @property ?CategoryInterface $category
 * @property ?LanguageInterface $language
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
     * @param ?string $originalUrl
     * @param ?string $url
     * @param ?string $slug
     * @param ?Crawler $crawler
     * @param ?BookDetailInterface $detail
     * @param ?CategoryInterface $category
     * @param ?LanguageInterface $language
     *
     * @return void
     */
    public function __construct(
        string $image = null,
        string $title = null,
        string $author = null,
        string $price = null,
        string $originalUrl = null,
        string $url = null,
        string $slug = null,
        Crawler $crawler = null,
        BookDetailInterface $detail = null,
        CategoryInterface $category = null,
        LanguageInterface $language = null,
    );

    /**
     * Get all books in the current page.
     *
     * @return Paginator
     */
    public function paginate(int $page = 1): Paginator;

    /**
     * Get a book by its slug.
     *
     * @param string $slug
     *
     * @return self
     */
    public function find(string $slug): self;

    /**
     * Set the book category.
     *
     * @return self
     */
    public function withCategory(CategoryInterface $category): self;

    /**
     * Set the book language.
     *
     * @return self
     */
    public function withLanguage(LanguageInterface $language): self;

    /**
     * Load a book detail.
     *
     * @return self
     */
    public function loadDetail(): self;
}
