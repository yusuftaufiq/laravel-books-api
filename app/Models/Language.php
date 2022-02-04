<?php

namespace App\Models;

use App\Enums\LanguageEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Language extends AbstractCrawler
{
    public ?string $slug = null;

    public ?string $name = null;

    public ?int $value = null;

    final public function all(): array
    {
        $categories = collect(LanguageEnum::cases())->map(fn (LanguageEnum $languageEnum) => [
            'slug' => $languageEnum->value,
            'name' => $languageEnum->name,
            'value' => $languageEnum->value(),
        ]);

        return $categories->toArray();
    }

    final public function find(string $slug): static
    {
        $languageEnum = LanguageEnum::tryFrom($slug);

        if ($languageEnum === null) {
            throw new NotFoundHttpException('Language not found');
        }

        $this->slug = $languageEnum->value;
        $this->name = $languageEnum->name;
        $this->value = $languageEnum->value();

        return $this;
    }

    final public function getRouteKey(): string
    {
        return $this->slug;
    }

    final public function getRouteKeyName(): string
    {
        return 'language';
    }

    final public function isEmpty(): bool
    {
        return $this->slug === null && $this->value === null;
    }

    final public function toArray(): array
    {
        return match ($this->isEmpty()) {
            false => [
                'slug' => $this->slug,
                'name' => $this->name,
                'value' => $this->value,
            ],
            default => [],
        };
    }
}
