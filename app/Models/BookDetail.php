<?php

namespace App\Models;

use App\Contracts\BookDetailInterface;
use Symfony\Component\DomCrawler\Crawler;

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

    final public function __construct(
        public ?string $releaseDate = null,
        public ?string $description = null,
        public ?string $language = null,
        public ?string $country = null,
        public ?string $publisher = null,
        public ?int $pageCount = null,
        public ?string $category = null,
        public ?string $slug = null,
        public ?Crawler $crawler = null,
    ) {
    }

    /**
     * Get part of the book details.
     *
     * @param  string  $part    Part of the book details.
     *
     * @return string
     */
    private function getDetailOf(string $part): string
    {
        return $this->crawler->filter(".switch_content.sc_2 td:contains(\"$part\")")
            ->closest('tr')
            ->filter('td')
            ->last()
            ->text();
    }

    final public function find(string $slug): static
    {
        if ($this->crawler->getUri() === null) {
            $this->crawler = \Book::find($slug)->crawler;
        }

        $this->releaseDate = str($this->crawler->filter('.switch_content.sc_1')->text())
            ->after(search: 'Release Date: ')
            ->before(search: '.');
        $this->description = $this->crawler->filter('[itemprop="description"]')->text();
        $this->language = $this->getDetailOf('Language');
        $this->country = $this->getDetailOf('Country');
        $this->publisher = $this->getDetailOf('Publisher');
        $this->pageCount = $this->getDetailOf('Page Count');
        $this->category = $this->crawler->filter('[itemprop="title"]')->eq(2)->text();
        $this->categorySlug = str($this->crawler->filter('[itemprop="url"].non')->eq(2)->attr('href'))
            ->afterLast(search: Category::BASE_URL);

        return $this;
    }
}
