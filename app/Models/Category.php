<?php

namespace App\Models;

use App\Enums\CategoryEnum;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Category implements UrlRoutable, Arrayable
{
    public ?string $slug = null;

    public ?string $name = null;

    private function setProperties(CategoryEnum $categoryEnum): self
    {
        $this->slug = $categoryEnum->value;
        $this->name = $categoryEnum->name();

        return $this;
    }

    final public function all(): array
    {
        $categories = collect(CategoryEnum::cases())->map(fn (CategoryEnum $categoryEnum) => [
            'slug' => $categoryEnum->value,
            'name' => $categoryEnum->name(),
        ]);

        return $categories->toArray();
    }

    final public function find(string $slug): self
    {
        $categoryEnum = CategoryEnum::tryFrom($slug);

        if ($categoryEnum === null) {
            throw new NotFoundHttpException('Category not found');
        }

        $this->setProperties($categoryEnum);

        return $this;
    }

    final public function books(int $page = 1): array
    {
        return (new Book())->all($page, $this);
    }

    /**
     * Get the value of the model's route key.
     *
     * @return string
     */
    final public function getRouteKey(): string
    {
        return $this->slug;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    final public function getRouteKeyName(): string
    {
        return 'category';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    final public function resolveRouteBinding($value, $field = null): self
    {
        return tap(new self(), function (self $instance) use ($value) {
            $instance->find($value);
        });
    }

    /**
     * Retrieve the child model for a bound value.
     *
     * @param  string  $childType
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    final public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception(self::class . ' does not support child bindings.');
    }

    final public function isEmpty(): bool
    {
        return $this->slug === null && $this->name === null;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
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
