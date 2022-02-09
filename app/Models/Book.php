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
        'url',
        'slug',
        'title',
        'image',
        'price',
        'author',
    ];

    protected array $countable = [
        'url',
        'slug',
        'title',
        'author',
    ];

    protected ?string $url = null;

    protected ?string $slug = null;

    protected ?string $title = null;

    protected ?string $image = null;

    protected ?string $author = null;

    protected ?string $price = null;

    private ?Crawler $crawler = null;

    private ?BookDetailInterface $detail = null;

    final public function getCrawler(): ?Crawler
    {
        return $this->crawler;
    }

    final public function all(
        ?CategoryInterface $category = null,
        ?LanguageInterface $language = null,
        int $page = 1
    ): array {
        $request = \Goutte::request(method: 'GET', uri: self::BASE_URL . '?' . \Arr::query([
            'page' => $page,
            'category' => $category->getSlug(),
            'language' => $language->getValue(),
        ]));

        $books = $request->filter('.oubox_list')->each(fn (Crawler $node) => [
            'url' => $node->filter('.title a')->attr('href'),
            'title' => $node->filter('.title a')->text(),
            'image' => $node->filter('.imgwrap img')->attr('src'),
            'price' => $node->filter('.price')->text(),
            'author' => $node->filter('.date')->text(),
            'slug' => str($node->filter('.title a')->attr('href'))->substr(start: str()->length(self::BASE_URL)),
        ]);

        return $books;
    }

    final public function find(string $slug): static
    {
        $this->crawler = \Goutte::request(method: 'GET', uri: self::BASE_URL . $slug);

        if (str($this->crawler->getUri())->contains(needles: '?ref')) {
            throw new NotFoundHttpException('Book not found');
        }

        $this->url = self::BASE_URL . $slug;
        $this->slug = $slug;
        $this->title = $this->crawler->filter('#big')->text();
        $this->image = $this->crawler->filter('#zoom img')->attr('src');
        $this->author = $this->crawler->filter('.auth a')->text();
        $this->price = str($this->crawler->filter('#content_data_trigger .plan_list div')->text())
            ->afterLast(search: ')');

        return $this;
    }

    final public function details(): array
    {
        if ($this->detail === null) {
            $this->detail = \BookDetail::setBook($this);
        }

        if ($this->detail->getSlug() !== $this->slug) {
            $this->detail->find($this->slug);
        }

        return \Arr::collapse([$this->toArray(), $this->detail->toArray()]);
    }
}
