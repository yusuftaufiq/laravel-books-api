<?php

namespace Tests\Unit\Models\Book;

use App\Models\Book;
use Illuminate\Contracts\Pagination\Paginator;
use Tests\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class CrawlIndexPageTest extends TestCase
{
    private string $dummyHtmlContent  = <<<'HTML'
        <div class="oubox_list rollover left">
            <div class="top">
                <!-- -->
                <div class="imgwrap">
                    <div class="limit" style="height: auto; width: auto;">
                        <a href="https://ebooks.gramedia.com/books/the-man-in-the-brown-suit"
                            onclick="gtm_product_detail( 'The Man in the Brown Suit by Agatha Christie Digital Book', '63078', '149000.00', 'The Man in the Brown Suit')">
                            <img src="https://ebooks.gramedia.com/ebook-covers/20214/thumb_image_normal/ID_HCO2015MTH01TMITBS.jpeg"
                                title="The Man in the Brown Suit by Agatha Christie Digital Book"
                                alt="The Man in the Brown Suit by Agatha Christie Cover" class="tall" style="display: block;">
                        </a>
                        <div class="rolloverbuild hide">
                            <a href="https://ebooks.gramedia.com/books/the-man-in-the-brown-suit"
                                class="blkbg"
                                onclick="gtm_product_detail('The Man in the Brown Suit by Agatha Christie Digital Book', '63078', '149000.00', 'The Man in the Brown Suit')">
                            </a>
                            <div class="wrap">
                                <a href="https://ebooks.gramedia.com/cart/add/63078?code=36647"
                                    class="auto_trigger subs_trigger" "="" id=" p_cart_63078">Add to Cart</a>
                                <a href="https://ebooks.gramedia.com/books/the-man-in-the-brown-suit"
                                    onclick="gtm_product_detail( 'The Man in the Brown Suit by Agatha Christie Digital Book', '63078', '149000.00', 'The Man in the Brown Suit')">DETAIL
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="desc">
                <h2 class="title">
                    <a href="https://ebooks.gramedia.com/books/the-man-in-the-brown-suit" class="limit"
                        onclick="gtm_product_detail('The Man in the Brown Suit by Agatha Christie Digital Book', '63078', '149000.00', 'The Man in the Brown Suit')">
                        The Man in the Brown Suit
                    </a>
                </h2>
                <div class="date">Agatha Christie</div>
                <div class="price"> <span>Rp</span> 149.000 </div>
            </div>
        </div>
        <div class="paging left">
            <a class="active">1</a>
            <a href="https://ebooks.gramedia.com/books?language=1&amp;category=historical-fiction&amp;page=2">2</a>
            <a href="https://ebooks.gramedia.com/books?language=1&amp;category=historical-fiction&amp;page=3">3</a>
            <a class="next" href="https://ebooks.gramedia.com/books?language=1&amp;category=historical-fiction&amp;page=2">&gt;</a>
        </div>
    HTML;

    public function testCrawlAllBooks(): void
    {
        $crawler = new Crawler();
        $crawler->addContent($this->dummyHtmlContent);

        \Goutte::shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturn($crawler);

        /** @var Book */
        $book = app(Book::class);
        $books = $book->all();
        $items = $books->items();

        $this->assertInstanceOf(expected: Paginator::class, actual: $books);
        $this->assertArrayHasKey(key: 0, array: $items);
        $this->assertInstanceOf(expected: Book::class, actual: $books[0]);

        /** @var Book */
        $firstBook = $items[0];

        $this->assertSame(
            expected: 'https://ebooks.gramedia.com/ebook-covers/20214/thumb_image_normal/ID_HCO2015MTH01TMITBS.jpeg',
            actual: $firstBook->image,
        );
        $this->assertSame(expected: 'The Man in the Brown Suit', actual: $firstBook->title);
        $this->assertSame(expected: 'Agatha Christie', actual: $firstBook->author);
        $this->assertSame(expected: 'Rp 149.000', actual: $firstBook->price);
        $this->assertSame(expected: 'https://ebooks.gramedia.com/books/the-man-in-the-brown-suit', actual: $firstBook->originalUrl);
        $this->assertStringContainsString(needle: 'api/books/the-man-in-the-brown-suit', haystack: $firstBook->url);
        $this->assertSame(expected: 'the-man-in-the-brown-suit', actual: $firstBook->slug);
    }
}
