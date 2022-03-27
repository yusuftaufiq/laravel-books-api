<?php

namespace App\Models;

use App\Contracts\LanguageInterface;
use App\Enums\LanguageEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Language extends BaseModel implements LanguageInterface
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected string $primaryKey = 'slug';

    /**
     * Create a new language instance.
     *
     * @param ?string $slug
     * @param ?string $name
     * @param ?int $value
     *
     * @return void
     */
    final public function __construct(
        public ?string $slug = null,
        public ?string $name = null,
        public ?int $value = null,
    ) {
    }

    /**
     * Get all languages.
     *
     * @return array<LanguageInterface>
     */
    final public function all(): array
    {
        $categories = collect(LanguageEnum::cases())->map(fn (LanguageEnum $languageEnum): self => new self(
            slug: $languageEnum->value,
            name: $languageEnum->name,
            value: $languageEnum->value(),
        ));

        /** @var array<LanguageInterface> */
        return $categories->toArray();
    }

    /**
     * Get a language by its slug.
     *
     * @param string $slug
     *
     * @return self
     */
    final public function find(string $slug): self
    {
        $languageEnum = LanguageEnum::tryFrom($slug);

        if ($languageEnum === null) {
            throw new NotFoundHttpException("The language with slug $slug could not be found.");
        }

        $this->slug = $languageEnum->value;
        $this->name = $languageEnum->name;
        $this->value = $languageEnum->value();

        return $this;
    }
}
