<?php

namespace App\Models;

use App\Contracts\LanguageInterface;
use App\Enums\LanguageEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Language extends AbstractBaseModel implements LanguageInterface
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
    public function __construct(
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
    public function all(): array
    {
        /** @var array<LanguageInterface> */
        return collect(LanguageEnum::cases())->map(fn (LanguageEnum $languageEnum): self => new self(
            slug: $languageEnum->value,
            name: $languageEnum->name,
            value: $languageEnum->value(),
        ))->toArray();
    }

    /**
     * Get a language by its slug.
     *
     * @param string $slug
     *
     * @return self
     */
    public function find(string $slug): self
    {
        $languageEnum = LanguageEnum::tryFrom($slug);

        if ($languageEnum === null) {
            throw new NotFoundHttpException("The language with slug ${slug} could not be found.");
        }

        $this->slug = $languageEnum->value;
        $this->name = $languageEnum->name;
        $this->value = $languageEnum->value();

        return $this;
    }
}
