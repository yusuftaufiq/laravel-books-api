<?php

namespace Tests\Unit\Models\Book;

use App\Models\Book;
use Illuminate\Contracts\Pagination\Paginator;
use Tests\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class CrawlSearchPageTest extends TestCase
{
    private string $dummyHtmlContent  = <<<'HTML'
        <div class="search_list clearfix">
            <div class="count left">1.</div>
            <a href="https://ebooks.gramedia.com/books/ready-player-one" class="left">
                <div class="limit">
                    <img src="https://s3-ap-southeast-1.amazonaws.com/ebook-covers/42028/thumb_image_normal/ID_GPU2018MTH04RPON.jpg"
                        title="Ready Player One by Digital Book" alt="Ready Player One by Cover">
                </div>
            </a>
            <div class="desc left">
                <h2 class="title"> <a href="https://ebooks.gramedia.com/books/ready-player-one">Ready Player One</a> </h2>
                <div class="by"> By <a href="https://ebooks.gramedia.com/books/author/ernest-cline">Ernest Cline</a> </div>
                <div style="margin-top: 15px;"> Category : Science Fiction &amp; Fantasy, Teen &amp; Young Adult Fiction
                    <div class="breadcrumb clearfix">
                        <div class="left">
                            <a href="https://ebooks.gramedia.com/books" class="non" itemprop="url">
                                <span itemprop="title">Books</span>
                            </a>
                            <span class="triangle">▸</span>
                        </div>
                        <div class="left" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
                            <a href="https://ebooks.gramedia.com/books/categories/science-fiction-fantasy" class="non"
                                itemprop="url">
                                <span itemprop="title">Science Fiction &amp; Fantasy</span>
                            </a>
                            <span class="triangle">▸</span>
                        </div>
                        <div class="left" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
                            <a href="https://ebooks.gramedia.com/books/ready-player-one" itemprop="url">
                                <span itemprop="title">Ready Player One</span>
                            </a>
                            <span class="triangle"></span>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 15px;"> Language: ind</div>
            </div>
        </div>
        <div class="paging left">
            <a class="active">1</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=2">2</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=3">3</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=4">4</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=5">5</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=6">6</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=7">7</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=8">8</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=9">9</a>
            <a href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=10">10</a>
            <a class="next" href="https://ebooks.gramedia.com/search?s=ready+player+one&amp;page=2">&gt;</a>
        </div>
    HTML;

    public function testCrawlBooksByKeyword()
    {
        $crawler = new Crawler();
        $crawler->addContent($this->dummyHtmlContent);

        \Goutte::shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturn($crawler);

        /** @var Book */
        $book = app(Book::class);
        $books = $book->like(keyword: 'Ready Player One');
        $items = $books->items();

        $this->assertInstanceOf(expected: Paginator::class, actual: $books);
        $this->assertArrayHasKey(key: 0, array: $items);
        $this->assertInstanceOf(expected: Book::class, actual: $books[0]);

        /** @var Book */
        $firstBook = $items[0];

        $this->assertSame(
            expected: 'https://s3-ap-southeast-1.amazonaws.com/ebook-covers/42028/thumb_image_normal/ID_GPU2018MTH04RPON.jpg',
            actual: $firstBook->image,
        );
        $this->assertSame(expected: 'Ready Player One', actual: $firstBook->title);
        $this->assertSame(expected: 'Ernest Cline', actual: $firstBook->author);
        $this->assertSame(expected: 'https://ebooks.gramedia.com/books/ready-player-one', actual: $firstBook->originalUrl);
        $this->assertStringContainsString(needle: 'api/books/ready-player-one', haystack: $firstBook->url);
        $this->assertSame(expected: 'ready-player-one', actual: $firstBook->slug);
    }
}
