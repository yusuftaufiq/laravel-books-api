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
     * Get the book detail by its slug.
     *
     * @param mixed $slug
     *
     * @return static
     */
    public function find(mixed $slug): static;
}
