<?php

namespace App\Models;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Book extends AbstractCrawler
{
    private const URL = 'https://ebooks.gramedia.com/books/';

    public ?string $originalUrl = null;

    public ?string $slug = null;

    public ?string $title = null;

    public ?string $image = null;

    public ?string $price = null;

    public ?string $writer = null;

    final public function all(int $page = 1, ?Category $category = null): array
    {
        $request = \Goutte::request('GET', self::URL, ['query' => [
            'page' => $page,
            'category' => $category?->slug,
        ]]);

        $books = $request->filter('.oubox_list')->each(fn (Crawler $node) => [
            'originalUrl' => $node->filter('.title a')->attr('href'),
            'slug' => \Str::substr($node->filter('.title a')->attr('href'), \Str::length(self::URL)),
            'title' => $node->filter('.title a')->text(),
            'image' => $node->filter('.imgwrap img')->attr('src'),
            'price' => $node->filter('.price')->text(),
            'writer' => $node->filter('.date')->text(),
        ]);

        return $books;
    }

    final public function find(string $slug): static
    {
        $request = \Goutte::request('GET', self::URL . $slug);

        if (\Str::contains($request->getUri(), '?ref')) {
            throw new NotFoundHttpException('Book not found');
        }

        $this->originalUrl = self::URL . $slug;
        $this->slug = $slug;
        $this->title = $request->filter('#big')->text();
        $this->image = $request->filter('#zoom img')->attr('src');
        $this->price = $request->filter('#content_data_trigger .plan_list div')->text();
        $this->writer = $request->filter('.auth a')->text();

        return $this;
    }

    final public function getRouteKey(): string
    {
        return $this->slug;
    }

    final public function getRouteKeyName(): string
    {
        return 'book';
    }

    final public function isEmpty(): bool
    {
        return $this->originalUrl === null
            && $this->slug === null
            && $this->title === null
            && $this->writer === null;
    }

    final public function toArray(): array
    {
        return match ($this->isEmpty()) {
            false => [
                'original_url' => $this->originalUrl,
                'slug' => $this->slug,
                'title' => $this->title,
                'image' => $this->image,
                'price' => $this->price,
                'writer' => $this->writer,
            ],
            default => [],
        };
    }
}
