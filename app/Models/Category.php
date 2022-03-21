<?php

namespace App\Models;

use App\Contracts\CategoryInterface;
use App\Enums\CategoryEnum;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Category extends BaseModel implements CategoryInterface
{
    final public const BASE_URL = 'https://ebooks.gramedia.com/books/categories/';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected string $primaryKey = 'slug';

    /**
     * Create a new category instance.
     *
     * @param ?string $slug
     * @param ?string $name
     *
     * @return void
     */
    public function __construct(
        public ?string $slug = null,
        public ?string $name = null,
    ) {
    }

    /**
     * Get all categories.
     *
     * @return array<CategoryInterface>
     */
    final public function all(): array
    {
        $categories = collect(CategoryEnum::cases())->map(fn (CategoryEnum $categoryEnum): self => new self(
            slug: $categoryEnum->value,
            name: $categoryEnum->name(),
        ));

        return $categories->toArray();
    }

    /**
     * Get a category by its slug.
     *
     * @param string $slug
     *
     * @return self
     */
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
