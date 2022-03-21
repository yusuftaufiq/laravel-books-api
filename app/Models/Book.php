<?php

namespace App\Models;

use App\Contracts\BookDetailInterface;
use App\Contracts\BookInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\LanguageInterface;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Pagination\Paginator;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Book extends BaseModel implements BookInterface
{
    /**
     * The base url of the site provides the list of books.
     */
    final public const BASE_URL = 'https://ebooks.gramedia.com/books/';

    /**
     * The search url of the site provides the list of books.
     */
    final public const SEARCH_URL = 'https://ebooks.gramedia.com/search/';

    /**
     * The number of books per index page.
     */
    final public const BOOKS_PER_PAGE = 24;

    /**
     * The number of books per search page.
     */
    final public const BOOKS_PER_SEARCH = 10;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected string $primaryKey = 'slug';

    /**
     * Create a new book instance.
     *
     * @param ?string $image
     * @param ?string $title
     * @param ?string $author
     * @param ?string $price
     * @param ?string $originalUrl
     * @param ?string $url
     * @param ?string $slug
     * @param ?Crawler $crawler
     * @param ?BookDetailInterface $detail
     * @param ?CategoryInterface $category
     * @param ?LanguageInterface $language
     *
     * @return void
     */
    final public function __construct(
        public ?string $image = null,
        public ?string $title = null,
        public ?string $author = null,
        public ?string $price = null,
        public ?string $originalUrl = null,
        public ?string $url = null,
        public ?string $slug = null,
        public ?Crawler $crawler = null,
        public ?BookDetailInterface $detail = null,
        public ?CategoryInterface $category = null,
        public ?LanguageInterface $language = null,
    ) {
    }

    /**
     * Get all books in the current page.
     *
     * @return Paginator
     */
    final public function all(int $page = 1): PaginatorContract
    {
        $crawler = \Goutte::request(method: 'GET', uri: self::BASE_URL . '?' . \Arr::query([
            'page' => $page,
            'category' => $this->category->slug,
            'language' => $this->language->value,
        ]));

        $books = $crawler->filter('.oubox_list')->each(function (Crawler $node) use ($crawler): self {
            $title = $node->filter('.title a');
            $originalUrl = $title->attr('href');
            $slug = str($originalUrl)->substr(start: str()->length(self::BASE_URL));

            return new self(
                image: $node->filter('.imgwrap img')->attr('src'),
                title: $title->text(),
                author: $node->filter('.date')->text(),
                price: $node->filter('.price')->text(),
                originalUrl: $originalUrl,
                url: route('books.show', ['book' => $slug]),
                slug: $slug,
                crawler: $crawler,
            );
        });

        $paginator = new Paginator($books, self::BOOKS_PER_PAGE, $page);
        $paginator->withPath(url(request()->fullUrlWithoutQuery('page')));
        $paginator->hasMorePagesWhen($crawler->filter('.paging .next')->count() > 0);

        return $paginator;
    }

    /**
     * Get a book by its slug.
     *
     * @param string $slug
     *
     * @return self
     */
    final public function find(string $slug): self
    {
        $this->crawler = \Goutte::request(method: 'GET', uri: self::BASE_URL . $slug);

        if (str($this->crawler->getUri())->contains(needles: '?ref')) {
            throw new NotFoundHttpException("The book with slug $slug could not be found.");
        }

        $this->image = $this->crawler->filter('#zoom img')->attr('src');
        $this->title = $this->crawler->filter('#big')->text();
        $this->author = $this->crawler->filter('.auth a')->text();
        $this->price = str($this->crawler->filter('#content_data_trigger .plan_list div')->text())->afterLast(search: ')');
        $this->originalUrl = self::BASE_URL . $slug;
        $this->url = route('books.show', ['book' => $slug]);
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set the book category.
     *
     * @return self
     */
    final public function withCategory(CategoryInterface $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Set the book language.
     *
     * @return self
     */
    final public function withLanguage(LanguageInterface $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Load a book detail.
     *
     * @return self
     */
    final public function loadDetail(): self
    {
        if ($this->detail->crawler->getUri() === null) {
            $this->detail->crawler = $this->crawler;
        }

        if ($this->detail->slug !== $this->slug) {
            $this->detail->find($this->slug);
        }

        return $this;
    }

    /**
     * Get all books by keyword in current page.
     *
     * @return Paginator
     */
    final public function like(string $keyword, int $page = 1): PaginatorContract
    {
        $crawler = \Goutte::request(method: 'GET', uri: self::SEARCH_URL . '?' . \Arr::query([
            's' => $keyword,
            'page' => $page,
            'it' => 'book',
        ]));

        $books = $crawler->filter('.search_list')->each(function (Crawler $node) use ($crawler): self {
            $title = $node->filter('.title a');
            $originalUrl = $title->attr('href');
            $slug = str($originalUrl)->substr(start: str()->length(self::BASE_URL));

            return new self(
                image: $node->filter('.limit img')->attr('src'),
                title: $title->text(),
                author: $node->filter('.by a')->text(),
                originalUrl: $originalUrl,
                url: route('books.show', ['book' => $slug]),
                slug: $slug,
                crawler: $crawler,
            );
        });

        $paginator = new Paginator($books, self::BOOKS_PER_SEARCH, $page);
        $paginator->withPath(url(request()->fullUrlWithoutQuery('page')));
        $paginator->hasMorePagesWhen($crawler->filter('.paging .next')->count() > 0);

        return $paginator;
    }
}
