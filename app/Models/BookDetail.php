<?php

namespace App\Models;

use App\Contracts\BookDetailInterface;
use Symfony\Component\DomCrawler\Crawler;

final class BookDetail extends AbstractBaseModel implements BookDetailInterface
{
    protected string $primaryKey = 'slug';

    /**
     * Create a new book detail instance.
     *
     * @param ?string $releaseDate
     * @param ?string $description
     * @param ?string $language
     * @param ?string $country
     * @param ?string $publisher
     * @param ?int $pageCount
     * @param ?string $category
     * @param ?string $categorySlug
     * @param ?string $slug
     * @param ?Crawler $crawler
     *
     * @return void
     */
    public function __construct(
        public ?string $releaseDate = null,
        public ?string $description = null,
        public ?string $language = null,
        public ?string $country = null,
        public ?string $publisher = null,
        public ?int $pageCount = null,
        public ?string $category = null,
        public ?string $categorySlug = null,
        public ?string $slug = null,
        public ?Crawler $crawler = null,
    ) {
    }

    /**
     * Get a book detail by its slug.
     *
     * @param string $slug
     *
     * @return self
     */
    public function find(string $slug): self
    {
        if ($this->crawler?->getUri() === null) {
            $this->crawler = \Goutte::request(method: 'GET', uri: Book::BASE_URL . $slug);
        }

        $this->releaseDate = \Str::of($this->crawler->filter('.switch_content.sc_1')->text())
            ->after(search: 'Release Date: ')
            ->before(search: '.');
        $this->description = $this->crawler->filter('[itemprop="description"]')->text();
        $this->language = $this->getDetailOf('Language');
        $this->country = $this->getDetailOf('Country');
        $this->publisher = $this->getDetailOf('Publisher');
        $this->pageCount = (int) $this->getDetailOf('Page Count');
        $this->category = $this->crawler->filter('[itemprop="title"]')->eq(2)->text();
        $this->categorySlug = \Str::afterLast(
            subject: $this->crawler->filter('[itemprop="url"].non')->eq(2)->attr('href') ?? '',
            search: Category::BASE_URL,
        );
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get part of the book details.
     *
     * @param  string  $part    Part of the book details.
     *
     * @return ?string
     */
    private function getDetailOf(string $part): ?string
    {
        try {
            $result = $this->crawler
                ?->filter(".switch_content.sc_2 td:contains(\"${part}\")")
                ?->closest('tr')
                ?->filter('td')
                ?->last()
                ?->text();
        } catch (\Exception $e) {
            $result = null;
        } finally {
            return $result ?? null;
        }
    }
}
