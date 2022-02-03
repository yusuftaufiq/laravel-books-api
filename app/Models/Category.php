<?php

namespace App\Models;

use App\Enums\CategoryEnum;
use Illuminate\Contracts\Routing\UrlRoutable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Category implements UrlRoutable
{
    public string $slug;

    public string $name;

    private function setCategoryProperties(CategoryEnum $categoryEnum): self
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

        $this->setCategoryProperties($categoryEnum);

        return $this;
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->slug;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
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
    public function resolveRouteBinding($value, $field = null)
    {
        return tap(new self, function (self $instance) use ($value) {
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
    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception(self::class . ' does not support child bindings.');
    }
}
