<?php

namespace App\Contracts;

interface LanguageInterface extends BaseModelInterface
{
    /**
     * Get the language's value.
     *
     * @return null|string
     */
    public function getValue(): ?string;

    /**
     * Get all languages.
     *
     * @return array
     */
    public function all(): array;
}
