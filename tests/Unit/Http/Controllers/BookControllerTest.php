<?php

namespace Tests\Unit\Http\Controllers;

use App\Contracts\BookInterface;
use App\Http\Controllers\Api\BookController;
use App\Http\Resources\BookCollection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Mockery\MockInterface;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use WithFaker;

    public function testIndex(): void
    {
        $page = $this->faker->randomDigitNotZero();

        $this->mock(BookInterface::class, function (MockInterface $mock) use ($page): void {
            $mock->shouldReceive('withCategory')->withAnyArgs()->andReturnSelf();
            $mock->shouldReceive('withLanguage')->withAnyArgs()->andReturnSelf();
            $mock->shouldReceive('all')->with($page)->andReturn(new Paginator([], 0));
        });

        $this->mock(Request::class, function (MockInterface $mock) use ($page): void {
            $mock->shouldReceive('query')
                ->once()
                ->withSomeOfArgs('page')
                ->andReturn($page);
        });

        /** @var BookController $bookController */
        $bookController = $this->app->make(abstract: BookController::class);
        $books = $this->app->call([$bookController, 'index']);

        $this->assertInstanceOf(expected: BookCollection::class, actual: $books);
    }
}
