<?php

namespace App\Models;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;

final class BookDetail extends BaseModel implements BookDetailInterface
{
    protected string $primaryKey = 'slug';

    protected array $arrayable = [
        'releaseDate',
        'description',
        'language',
        'country',
        'publisher',
        'pageCount',
        'category',
    ];

    protected array $countable = [
        'description',
        'language',
        'publisher',
    ];

    protected ?string $slug = null;

    protected ?string $releaseDate = null;

    protected ?string $description = null;

    protected ?string $language = null;

    protected ?string $country = null;

    protected ?string $publisher = null;

    protected ?int $pageCount = null;

    protected ?string $category = null;

    private ?BookInterface $book = null;

    final public function getSlug(): ?string
    {
        return $this->slug;
    }

    final public function setBook(BookInterface $book): static
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get the book detail type.
     *
     * @param  string  $type    The type of book detail part.
     *
     * @return string
     */
    private function getDetailOf(string $type): string
    {
        return $this->book->getCrawler()->filter(".switch_content.sc_2 td:contains(\"$type\")")
            ->closest('tr')
            ->filter('td')
            ->last()
            ->text();
    }

    final public function find(mixed $slug): static
    {
        if ($this->book === null) {
            $this->book = new Book();
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
            subject: $this->book->getCrawler()->filter('[itemprop="url"].non')->eq(2)->attr('href'),
            search: Category::BASE_URL,
        );

        return $this;
    }
}
