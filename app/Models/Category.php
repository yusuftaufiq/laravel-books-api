<?php

namespace App\Models;

use App\Enums\CategoryEnum;

final class Category
{
    final public function all(): array
    {
        $categories = collect(CategoryEnum::cases())->map(fn (CategoryEnum $category) => [
            'slug' => $category->value,
            'name' => $category->name(),
        ]);

        return $categories->toArray();
    }
}
