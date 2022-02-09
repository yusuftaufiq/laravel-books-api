<?php

namespace App\Models;

use App\Contracts\CategoryInterface;
use App\Enums\CategoryEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Category extends BaseModel implements CategoryInterface
{
    final public const BASE_URL = 'https://ebooks.gramedia.com/books/categories/';

    protected string $primaryKey = 'slug';

    protected array $arrayable = [
        'slug',
        'name',
    ];

    protected array $countable = [
        'slug',
        'name',
    ];

    protected ?string $slug = null;

    protected ?string $name = null;

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
}
