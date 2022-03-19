<?php

namespace Tests\Unit\Http\Controllers;

use App\Contracts\BookInterface;
use App\Http\Controllers\Api\BookSearchController;
use App\Http\Resources\BookCollection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Mockery\MockInterface;
use Tests\TestCase;

class BookSearchControllerTest extends TestCase
{
    use WithFaker;

    public function testIndex(): void
    {
        $keyword = $this->faker->word;
        $page = $this->faker->randomDigitNotZero();

        $this->mock(BookInterface::class, function (MockInterface $mock) use ($keyword, $page): void {
            $mock->shouldReceive('like')
                ->once()
                ->with($keyword, $page)
                ->andReturn(new Paginator([], 0));
        });

        $this->mock(Request::class, function (MockInterface $mock) use ($page): void {
            $mock->shouldReceive('query')->once()->withSomeOfArgs('page')->andReturn($page);
        });

        /** @var BookSearchController */
        $bookSearchController = $this->app->make(abstract: BookSearchController::class);
        $books = $this->app->call($bookSearchController, parameters: ['keyword' => $keyword]);

        $this->assertInstanceOf(expected: BookCollection::class, actual: $books);
    }
}
