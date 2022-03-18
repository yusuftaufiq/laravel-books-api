<?php

namespace Tests\Unit\Models\Book;

use App\Models\Book;
use App\Models\BookDetail;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class CrawlDetailPageTest extends TestCase
{
    private string $dummyHtmlContent = <<<'HTML'
        <div id="breadcrumb" class="center center-breadcrumb clearfix">
            <div class="left">
                <a href="https://ebooks.gramedia.com/en" class="non" itemprop="url">
                    <span itemprop="title">Home</span>
                </a>
                <span class="triangle">▸</span>
            </div>
            <div class="left" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
                <a href="https://ebooks.gramedia.com/books" class="non" itemprop="url">
                    <span itemprop="title">Books</span>
                </a>
                <span class="triangle">▸</span>
            </div>
            <div class="left" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
                <a href="https://ebooks.gramedia.com/books/categories/mystery-thriller-suspense" class="non" itemprop="url">
                    <span itemprop="title">Mystery, Thriller &amp; Suspense</span>
                </a>
                <span class="triangle">▸</span>
            </div>
            <div class="left" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
                <a href="https://ebooks.gramedia.com/books/the-adventures-of-sherlock-holmes" itemprop="url">
                    <span itemprop="title">The Adventures of Sherlock Holmes</span>
                </a>
                <span class="triangle"></span>
            </div>
        </div>
        <div id="content_detail_title">
            <span id="big">The Adventures of Sherlock Holmes</span>
            <h1 class="title hide" itemprop="name">The Adventures Of Sherlock Holmes Book by Arthur F. Carmazzi</h1><br>
            <p class="auth">By <a href="https://ebooks.gramedia.com/books/author/arthur-f-carmazzi">Arthur F. Carmazzi</a> </p>
        </div>
        <div id="content_detail_top" class="clearfix">
            <div class="left">
                <div class="limit" style="height: auto; width: auto;">
                    <a id="zoom" href="https://ebooks.gramedia.com/ebook-covers/24943/big_covers/ID_HCO2015MTH06TAOSH_B.jpg">
                        <img src="https://ebooks.gramedia.com/ebook-covers/24943/big_covers/ID_HCO2015MTH06TAOSH_B.jpg"
                            title="The Adventures of Sherlock Holmes by Arthur F. Carmazzi Digital Book"
                            alt="The Adventures of Sherlock Holmes by Arthur F. Carmazzi Digital Book" class="tall"
                            style="display: block;">
                        </a>
                    </div>
            </div>
            <div class="right">
                <div id="tempIDPDPD" class="hide" data-id="78354"></div>
                <div id="content_data_trigger" class="">
                    <div class="plan_list">
                        <table>
                            <tbody>
                                <tr class="" style="order: 1">
                                    <td class="long three-col-first-child"> Single Issue <div><span>Rp</span> 29.000</div>
                                    </td>
                                    <td class="grey three-col-second-child"> </td>
                                    <td colspan="2" class="three-col-third-child">
                                        <a href="https://ebooks.gramedia.com/cart/add/78354?code=44326"
                                            class="ga_subscription_plan auto_trigger subs_button subs_trigger"
                                            onclick="gtm_add_to_cart('The Adventures of Sherlock Holmes', '78354', '29000.00', 'Harper Collins')">
                                            Add to Cart
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="content_tab">
            <div class="switch_content sc_1" style="display: block;"> Release Date: 26 June 2015.
                <div itemprop="description">
                    <p>The first collection of stories featuring the legendary detective Sherlock Holmes, one of the most famous
                        and beloved detectives in fiction</p>
                    <p>In the riveting tales collected in , the sleuth of 221B Baker Street and his steadfast companion, Watson,
                        set out on the dark, foggy streets of late Victorian London to solve England's darkest mysteries and
                        unearth its most closely guarded secrets. In these stories, the beloved detective uses razor-sharp logic
                        and brilliant analytical reasoning to rescue a king from blackmail, find a missing fianc, infiltrate an
                        opium den, and solve many other mysteries. The adventures of Sherlock Holmes remain as thrilling,
                        surprising, and entertaining as they were more than a century ago.</p>
                </div>
            </div>
            <div class="switch_content sc_2" style="display: none;">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 25%">Language</td>
                            <td style="width: 4%"> : </td>
                            <td>English</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td> : </td>
                            <td>Indonesia</td>
                        </tr>
                        <tr>
                            <td>Publisher</td>
                            <td> : </td>
                            <td><a href="https://ebooks.gramedia.com/publishers/harper-collins">Harper Collins</a></td>
                        </tr>
                        <tr>
                            <td>Author</td>
                            <td> : </td>
                            <td> <a href="https://ebooks.gramedia.com/books/author/arthur-f-carmazzi">Arthur F. Carmazzi</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    HTML;

    public function testCrawlBookBySlug(): void
    {
        $crawler = new Crawler(uri: Book::BASE_URL . '/the-adventures-of-sherlock-holmes');
        $crawler->addContent($this->dummyHtmlContent);

        \Goutte::shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturn($crawler);

        /** @var Book */
        $book = app(Book::class);
        $book = $book->find(slug: 'the-adventures-of-sherlock-holmes')->loadDetail();

        $this->assertInstanceOf(expected: Book::class, actual: $book);
        $this->assertInstanceOf(expected: BookDetail::class, actual: $book->detail);

        $this->assertSame(
            expected: 'https://ebooks.gramedia.com/ebook-covers/24943/big_covers/ID_HCO2015MTH06TAOSH_B.jpg',
            actual: $book->image,
        );
        $this->assertSame(expected: 'The Adventures of Sherlock Holmes', actual: $book->title);
        $this->assertSame(expected: 'Arthur F. Carmazzi', actual: $book->author);
        $this->assertSame(expected: 'Rp 29.000', actual: $book->price);
        $this->assertSame(expected: 'https://ebooks.gramedia.com/books/the-adventures-of-sherlock-holmes', actual: $book->originalUrl);
        $this->assertStringContainsString(needle: 'api/books/the-adventures-of-sherlock-holmes', haystack: $book->url);
        $this->assertSame(expected: 'the-adventures-of-sherlock-holmes', actual: $book->slug);

        $this->assertSame(expected: '26 June 2015', actual: $book->detail->releaseDate);
        $this->assertStringContainsString(
            needle: 'The first collection of stories featuring the legendary detective Sherlock Holmes',
            haystack: $book->detail->description,
        );
        $this->assertSame(expected: 'English', actual: $book->detail->language);
        $this->assertSame(expected: 'Indonesia', actual: $book->detail->country);
        $this->assertSame(expected: 'Harper Collins', actual: $book->detail->publisher);
        $this->assertSame(expected: 'Mystery, Thriller & Suspense', actual: $book->detail->category);
    }
}
