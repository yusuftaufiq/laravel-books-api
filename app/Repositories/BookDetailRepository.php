<?php

namespace App\Models;

use Symfony\Component\DomCrawler\Crawler;

final class BookDetail
{
    public ?Crawler $crawler = null;

    public ?string $originUrl = null;

    public ?string $slug = null;

    public ?string $title = null;

    public ?string $image = null;

    public ?string $price = null;

    public ?string $author = null;

    public ?string $releaseDate = null;

    public ?string $description = null;

    public ?string $language = null;

    public ?string $country = null;

    public ?string $publisher = null;

    public ?int $pageCount = null;

    public ?string $category = null;

    public ?string $categorySlug = null;

    private function getDetailOf(string $type): string
    {
        return $this->crawler->filter(".switch_content.sc_2 td:contains(\"$type\")")
            ->closest('tr')
            ->filter('td')
            ->last()
            ->text();
    }

    final public function find(?Book $book = null, ?string $slug = null): static
    {
        $book = $book ?: (new Book())->find($slug);

        $this->crawler = $book->crawler;
        $this->originUrl = $book->originUrl;
        $this->slug = $book->slug;
        $this->title = $book->title;
        $this->image = $book->image;
        $this->price = $book->price;
        $this->author = $book->author;
        $this->releaseDate = $this->crawler->filter('.switch_content.sc_1')->first()->text();
        $this->description = $this->crawler->filter('[itemprop="description"]')->text();
        $this->language = $this->getDetailOf('Language');
        $this->country = $this->getDetailOf('Country');
        $this->publisher = $this->getDetailOf('Publisher');
        $this->pageCount = $this->getDetailOf('Page Count');
        $this->category = $this->crawler->filter('[itemprop="title"]')->eq(2)->text();
        $this->categorySlug = \Str::afterLast(
            $this->crawler->filter('[itemprop="url"].non')->eq(2)->attr('href'),
            Category::URL,
        );

        return $this;
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
                'release_date' => $this->releaseDate,
                'language' => $this->language,
                'country' => $this->country,
                'publisher' => $this->publisher,
                'page_count' => $this->pageCount,
                'category' => $this->category,
                'category_slug' => $this->categorySlug,
            ],
            default => [],
        };
    }
}
