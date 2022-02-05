<?php

namespace App\Models;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Book extends AbstractCrawler
{
    public const URL = 'https://ebooks.gramedia.com/books/';

    public ?Crawler $crawler = null;

    public ?string $originUrl = null;

    public ?string $slug = null;

    public ?string $title = null;

    public ?string $image = null;

    public ?string $price = null;

    public ?string $author = null;

    final public function all(?Category $category = null, ?Language $language = null, int $page = 1): array
    {
        $request = \Goutte::request('GET', self::URL . '?' . http_build_query([
            'page' => $page,
            'category' => $category?->slug,
            'language' => $language?->value,
        ]));

        $books = $request->filter('.oubox_list')->each(fn (Crawler $node) => [
            'originUrl' => $node->filter('.title a')->attr('href'),
            'slug' => \Str::substr($node->filter('.title a')->attr('href'), \Str::length(self::URL)),
            'title' => $node->filter('.title a')->text(),
            'image' => $node->filter('.imgwrap img')->attr('src'),
            'price' => $node->filter('.price')->text(),
            'author' => $node->filter('.date')->text(),
        ]);

        return $books;
    }

    final public function find(string $slug): static
    {
        $this->crawler = \Goutte::request('GET', self::URL . $slug);

        if (\Str::contains($this->crawler->getUri(), '?ref')) {
            throw new NotFoundHttpException('Book not found');
        }

        $this->originUrl = self::URL . $slug;
        $this->slug = $slug;
        $this->title = $this->crawler->filter('#big')->text();
        $this->image = $this->crawler->filter('#zoom img')->attr('src');
        $this->price = \Str::afterLast($this->crawler->filter('#content_data_trigger .plan_list div')->text(), ')');
        $this->author = $this->crawler->filter('.auth a')->text();

        return $this;
    }

    public function details(): array
    {
        return (new BookDetail())->find($this)->toArray();
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
        return $this->originUrl === null
            && $this->slug === null
            && $this->title === null
            && $this->author === null;
    }

    final public function toArray(): array
    {
        return match ($this->isEmpty()) {
            false => [
                'origin_url' => $this->originUrl,
                'slug' => $this->slug,
                'title' => $this->title,
                'image' => $this->image,
                'price' => $this->price,
                'author' => $this->author,
            ],
            default => [],
        };
    }
}
