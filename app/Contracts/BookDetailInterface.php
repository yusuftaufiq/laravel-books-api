<?php

namespace App\Contracts;

interface BookDetailInterface extends CrawlerInterface
{
    /**
     * Get the book's slug.
     *
     * @return null|string
     */
    public function getSlug(): ?string;

    /**
     * Get the book's release date.
     *
     * @return null|string
     */
    public function getReleaseDate(): ?string;

    /**
     * Get the book's description.
     *
     * @return null|string
     */
    public function getDescription(): ?string;

    /**
     * Get the book's language.
     *
     * @return null|string
     */
    public function getLanguage(): ?string;

    /**
     * Get the book's country.
     *
     * @return null|string
     */
    public function getCountry(): ?string;

    /**
     * Get the book's publisher.
     */
    public function getPublisher(): ?string;

    /**
     * Get the book's page count.
     *
     * @return null|int
     */
    public function getPageCount(): ?int;

    /**
     * Get the book's category.
     *
     * @return null|string
     */
    public function getCategory(): ?string;

    /**
     * Set the book's parent.
     *
     * @param BookInterface $book
     *
     * @return static
     */
    public function setBook(BookInterface $book): static;
}
