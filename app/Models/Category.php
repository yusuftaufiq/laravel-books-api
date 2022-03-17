<?php

namespace App\Models;

use App\Contracts\CategoryInterface;
use App\Enums\CategoryEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Category extends BaseModel implements CategoryInterface
{
    final public const BASE_URL = 'https://ebooks.gramedia.com/books/categories/';

    protected string $primaryKey = 'slug';

    public function __construct(
        public ?string $slug = null,
        public ?string $name = null,
    ) {
    }

    final public function all(): array
    {
        $categories = collect(CategoryEnum::cases())->map(fn (CategoryEnum $categoryEnum): self => new self(
            slug: $categoryEnum->value,
            name: $categoryEnum->name(),
        ));

        return $categories->toArray();
    }

    final public function find(string $slug): self
    {
        $categoryEnum = CategoryEnum::tryFrom($slug);

        if ($categoryEnum === null) {
            throw new NotFoundHttpException("The category with slug $slug could not be found.");
        }

        $this->slug = $categoryEnum->value;
        $this->name = $categoryEnum->name();

        return $this;
    }
}
