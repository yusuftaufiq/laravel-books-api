<?php

namespace App\Contracts;

interface LanguageInterface extends CrawlerInterface
{
    /**
     * Get the language's value.
     *
     * @return null|string
     */
    public function getValue(): ?string;

    /**
     * Get the language's name.
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Get the language's slug.
     *
     * @return null|string
     */
    public function getSlug(): ?string;

    /**
     * Get all languages.
     *
     * @return array
     */
    public function all(): array;
}
