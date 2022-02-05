<?php

namespace App\Models;

use App\Enums\CategoryEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Category extends AbstractCrawler
{
    public const URL = 'https://ebooks.gramedia.com/books/categories/';

    public ?string $slug = null;

    public ?string $name = null;

    final public function all(): array
    {
        $categories = collect(CategoryEnum::cases())->map(fn (CategoryEnum $categoryEnum) => [
            'slug' => $categoryEnum->value,
            'name' => $categoryEnum->name(),
        ]);

        return $categories->toArray();
    }

    final public function find(string $slug): static
    {
        $categoryEnum = CategoryEnum::tryFrom($slug);

        if ($categoryEnum === null) {
            throw new NotFoundHttpException('Category not found');
        }

        $this->slug = $categoryEnum->value;
        $this->name = $categoryEnum->name();

        return $this;
    }

    final public function books(?Language $language = null, int $page = 1): array
    {
        return (new Book())->all($this, $language, $page);
    }

    final public function getRouteKey(): string
    {
        return $this->slug;
    }

    final public function getRouteKeyName(): string
    {
        return 'category';
    }

    final public function isEmpty(): bool
    {
        return $this->slug === null && $this->name === null;
    }

    final public function toArray(): array
    {
        return match ($this->isEmpty()) {
            false => [
                'slug' => $this->slug,
                'name' => $this->name,
            ],
            default => [],
        };
    }
}
