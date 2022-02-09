<?php

namespace App\Contracts;

interface BookDetailInterface extends BaseModelInterface
{
    /**
     * Get the book's slug.
     *
     * @return null|string
     */
    public function getSlug(): ?string;

    /**
     * Set the book's parent.
     *
     * @param BookInterface $book
     *
     * @return static
     */
    public function setBook(BookInterface $book): static;

    /**
     * Get a book detail by its slug.
     *
     * @param string $slug
     *
     * @return static
     */
    public function find(string $slug): static;
}
