<?php

namespace App\Models;

final class Book
{
    private const URL = 'https://ebooks.gramedia.com/books/';

    final public function all(int $page = 1, ?Category $category = null): array
    {
        return [];
    }
}
