<?php

namespace App\Repositories;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use App\Repositories\CrawlerRepository;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BookRepository extends CrawlerRepository implements BookInterface
{
    final public const BASE_URL = 'https://ebooks.gramedia.com/books/';

    private ?Crawler $crawler = null;

    private ?string $url = null;

    private ?string $slug = null;

    private ?string $title = null;

    private ?string $image = null;

    private ?string $price = null;

    private ?string $author = null;

    private ?BookDetailInterface $detail = null;

    final public function getCrawler(): ?Crawler
    {
        return $this->crawler;
    }
    final public function getSlug(): ?string
    {
        return $this->slug;
    }

    final public function getTitle(): ?string
    {
        return $this->title;
    }

    final public function getAuthor(): ?string
    {
        return $this->author;
    }

    final public function getPrice(): ?float
    {
        return $this->price;
    }

    final public function getImageUrl(): ?string
    {
        return $this->image;
    }

    final public function getUrl(): ?string
    {
        return $this->url;
    }

    final public function all(
        ?CategoryInterface $category = null,
        ?LanguageInterface $language = null,
        int $page = 1
    ): array {
        $request = \Goutte::request('GET', self::BASE_URL . '?' . http_build_query([
            'page' => $page,
            'category' => $category->getSlug(),
            'language' => $language->getValue(),
        ]));

        $books = $request->filter('.oubox_list')->each(fn (Crawler $node) => [
            'url' => $node->filter('.title a')->attr('href'),
            'slug' => \Str::substr($node->filter('.title a')->attr('href'), \Str::length(self::BASE_URL)),
            'title' => $node->filter('.title a')->text(),
            'image' => $node->filter('.imgwrap img')->attr('src'),
            'price' => $node->filter('.price')->text(),
            'author' => $node->filter('.date')->text(),
        ]);

        return $books;
    }

    final public function find(string $slug): static
    {
        $this->crawler = \Goutte::request('GET', self::BASE_URL . $slug);

        if (\Str::contains($this->crawler->getUri(), '?ref')) {
            throw new NotFoundHttpException('Book not found');
        }

        $this->url = self::BASE_URL . $slug;
        $this->slug = $slug;
        $this->title = $this->crawler->filter('#big')->text();
        $this->image = $this->crawler->filter('#zoom img')->attr('src');
        $this->price = \Str::afterLast($this->crawler->filter('#content_data_trigger .plan_list div')->text(), ')');
        $this->author = $this->crawler->filter('.auth a')->text();

        return $this;
    }

    final public function details(): array
    {
        if ($this->detail === null) {
            $this->detail = new BookDetailRepository();
            $this->detail->setBook($this);
        }

        if ($this->detail->getSlug() !== $this->slug) {
            $this->detail->find($this->slug);
        }

        return \Arr::collapse($this->toArray(), $this->detail->toArray());
    }

    final public function count(): int
    {
        return $this->url !== null
            && $this->slug !== null
            && $this->title !== null
            && $this->author !== null;
    }

    final public function toArray(): array
    {
        return match ($this->count()) {
            0 => [],
            default => [
                'url' => $this->url,
                'slug' => $this->slug,
                'title' => $this->title,
                'image' => $this->image,
                'price' => $this->price,
                'author' => $this->author,
            ],
        };
    }
}
