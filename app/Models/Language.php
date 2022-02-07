<?php

namespace App\Repositories;

use App\Contracts\LanguageInterface;
use App\Enums\LanguageEnum;
use App\Repositories\CrawlerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class LanguageRepository extends CrawlerRepository implements LanguageInterface
{
    private ?string $slug = null;

    private ?string $name = null;

    private ?int $value = null;

    final public function getValue(): ?string
    {
        return $this->value;
    }

    final public function getName(): ?string
    {
        return $this->name;
    }

    final public function getSlug(): ?string
    {
        return $this->slug;
    }

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

    final public function count(): int
    {
        return $this->slug !== null && $this->value !== null && $this->name !== null;
    }

    final public function toArray(): array
    {
        return match ($this->count()) {
            0 => [],
            default => [
                'slug' => $this->slug,
                'name' => $this->name,
                'value' => $this->value,
            ],
        };
    }

    final public function getRouteKey(): int|string
    {
        return $this->slug;
    }

    final public function getRouteKeyName(): string
    {
        return 'language';
    }
}
