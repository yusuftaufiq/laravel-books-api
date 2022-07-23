<?php

namespace Tests\Unit\Http\Controllers;

use App\Contracts\BookInterface;
use App\Http\Controllers\Api\BookDetailController;
use App\Http\Resources\BookResource;
use Mockery\MockInterface;
use Tests\TestCase;

class BookDetailControllerTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeInstanceOfBookResourceClassIfRequestSuccessful(): void
    {
        $this->mock(BookInterface::class, function (MockInterface $mock): void {
            $mock->shouldReceive('loadDetail')->once()->withNoArgs()->andReturnSelf();
        });

        /** @var BookDetailController $bookDetailController */
        $bookDetailController = $this->app->make(abstract: BookDetailController::class);
        $bookWithDetail = $this->app->call([$bookDetailController, 'index']);

        $this->assertInstanceOf(expected: BookResource::class, actual: $bookWithDetail);
    }
}
