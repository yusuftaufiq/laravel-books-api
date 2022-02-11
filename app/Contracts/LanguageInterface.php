<?php

namespace App\Contracts;

/**
 * @property ?string $slug
 * @property ?string $name
 * @property ?int $value
 */
interface LanguageInterface extends BaseModelInterface
{
    /**
     * Create a new language instance.
     *
     * @param ?string $slug
     * @param ?string $name
     * @param ?int $value
     *
     * @return void
     */
    public function __construct(?string $slug = null, ?string $name = null, ?int $value = null);

    /**
     * Get all languages.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Get a language by its slug.
     *
     * @param string $slug
     *
     * @return static
     */
    public function find(string $slug): static;
}
