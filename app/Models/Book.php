<?php

namespace App\Models;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Book extends BaseModel implements BookInterface
{
    final public const BASE_URL = 'https://ebooks.gramedia.com/books/';

    protected string $primaryKey = 'slug';

    protected array $arrayable = [
        'image',
        'title',
        'author',
        'price',
        'url',
        'slug',
    ];

    protected array $countable = [
        'title',
        'author',
        'url',
        'slug',
    ];

    final public function __construct(
        public ?string $image = null,
        public ?string $title = null,
        public ?string $author = null,
        public ?string $price = null,
        public ?string $url = null,
        public ?string $slug = null,
        public ?Crawler $crawler = null,
        public ?BookDetailInterface $details = null,
    ) {
    }

    /**
     * Get all books.
     *
     * @return array<BookInterface>
     */
    final public function all(
        ?CategoryInterface $category = null,
        ?LanguageInterface $language = null,
        int $page = 1
    ): array {
        $crawler = \Goutte::request(method: 'GET', uri: self::BASE_URL . '?' . \Arr::query([
            'page' => $page,
            'category' => $category->slug,
            'language' => $language->value,
        ]));

        $books = $crawler->filter('.oubox_list')->each(fn (Crawler $node) => new static(
            url: $node->filter('.title a')->attr('href'),
            title: $node->filter('.title a')->text(),
            image: $node->filter('.imgwrap img')->attr('src'),
            price: $node->filter('.price')->text(),
            author: $node->filter('.date')->text(),
            slug: str($node->filter('.title a')->attr('href'))->substr(start: str()->length(self::BASE_URL)),
            crawler: $crawler,
        ));

        return $books;
    }

    final public function find(string $slug): static
    {
        $this->crawler = \Goutte::request(method: 'GET', uri: self::BASE_URL . $slug);

        if (str($this->crawler->getUri())->contains(needles: '?ref')) {
            throw new NotFoundHttpException("The book with slug $slug could not be found.");
        }

        $this->image = $this->crawler->filter('#zoom img')->attr('src');
        $this->title = $this->crawler->filter('#big')->text();
        $this->author = $this->crawler->filter('.auth a')->text();
        $this->price = str($this->crawler->filter('#content_data_trigger .plan_list div')->text())->afterLast(search: ')');
        $this->url = self::BASE_URL . $slug;
        $this->slug = $slug;

        return $this;
    }

    final public function loadDetails(): static
    {
        if ($this->details->crawler->getUri() === null) {
            $this->details->crawler = $this->crawler;
        }

        if ($this->details->slug !== $this->slug) {
            $this->details->find($this->slug);
        }

        if (collect($this->arrayable)->contains('details') === false) {
            $this->arrayable[] = 'details';
        }

        return $this;
    }
}
