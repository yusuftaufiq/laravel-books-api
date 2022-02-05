<?php

namespace App\Repositories;

use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Enums\CategoryEnum;
use App\Repositories\CrawlerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CategoryRepository extends CrawlerRepository implements CategoryInterface
{
    final public const BASE_URL = 'https://ebooks.gramedia.com/books/categories/';

    private ?string $slug = null;

    private ?string $name = null;

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

    final public function books(LanguageInterface $language, int $page = 1): array
    {
        return (new BookRepository())->all($this, $language, $page);
    }

    final public function count(): int
    {
        return $this->slug !== null && $this->name !== null;
    }

    final public function toArray(): array
    {
        return match ($this->count()) {
            0 => [],
            default => [
                'slug' => $this->slug,
                'name' => $this->name,
            ],
        };
    }
}
