<?php

namespace App\Repositories;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Repositories\CrawlerRepository;

final class BookDetailRepository extends CrawlerRepository implements BookDetailInterface
{
    private ?BookInterface $book = null;

    private ?string $slug = null;

    private ?string $releaseDate = null;

    private ?string $description = null;

    private ?string $language = null;

    private ?string $country = null;

    private ?string $publisher = null;

    private ?int $pageCount = null;

    private ?string $category = null;

    final public function getSlug(): ?string
    {
        return $this->slug;
    }

    final public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    final public function getDescription(): ?string
    {
        return $this->description;
    }

    final public function getLanguage(): ?string
    {
        return $this->language;
    }

    final public function getCountry(): ?string
    {
        return $this->country;
    }

    final public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    final public function getPageCount(): int
    {
        return $this->pageCount;
    }

    final public function getCategory(): ?string
    {
        return $this->category;
    }

    final public function setBook(BookInterface $book): static
    {
        $this->book = $book;

        return $this;
    }

    private function getDetailOf(string $type): string
    {
        return $this->book->getCrawler()->filter(".switch_content.sc_2 td:contains(\"$type\")")
            ->closest('tr')
            ->filter('td')
            ->last()
            ->text();
    }

    final public function find(string $slug): static
    {
        if ($this->book === null) {
            $this->book = new BookRepository();
            $this->book->find($slug);
        }

        $this->releaseDate = $this->book->getCrawler()->filter('.switch_content.sc_1')->first()->text();
        $this->description = $this->book->getCrawler()->filter('[itemprop="description"]')->text();
        $this->language = $this->getDetailOf('Language');
        $this->country = $this->getDetailOf('Country');
        $this->publisher = $this->getDetailOf('Publisher');
        $this->pageCount = $this->getDetailOf('Page Count');
        $this->category = $this->book->getCrawler()->filter('[itemprop="title"]')->eq(2)->text();
        $this->categorySlug = \Str::afterLast(
            $this->book->getCrawler()->filter('[itemprop="url"].non')->eq(2)->attr('href'),
            CategoryRepository::BASE_URL,
        );

        return $this;
    }

    final public function count(): int
    {
        return $this->description !== null
            && $this->language !== null
            && $this->publisher !== null;
    }

    final public function toArray(): array
    {
        return match ($this->count()) {
            0 => [],
            default => [
                'releaseDate' => $this->releaseDate,
                'description' => $this->description,
                'language' => $this->language,
                'country' => $this->country,
                'publisher' => $this->publisher,
                'pageCount' => $this->pageCount,
                'category' => $this->category,
            ],
        };
    }

    final public function getRouteKey(): int|string
    {
        return $this->slug;
    }

    final public function getRouteKeyName(): string
    {
        return 'bookDetail';
    }
}
