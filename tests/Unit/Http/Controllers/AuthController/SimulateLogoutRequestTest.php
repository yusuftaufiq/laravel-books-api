<?php

namespace Tests\Unit\Http\Controllers\AuthController;

use App\Contracts\UserInterface;
use App\Http\Controllers\Api\AuthController;
use App\Http\Resources\PersonalAccessTokenResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery\MockInterface;
use Tests\TestCase;

class SimulateLogoutRequestTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function itShouldBeInstanceOfPersonalAccessTokenResourceClassIfRequestSuccessful(): void
    {
        $tokenMock = \Mockery::mock(PersonalAccessToken::class, function (MockInterface $mock): void {
            $mock->shouldReceive('delete')->once()->withNoArgs()->andReturnTrue();
        });
        $userMock = \Mockery::mock(UserInterface::class, function (MockInterface $mock) use ($tokenMock): void {
            $mock->shouldReceive('currentAccessToken')->once()->withNoArgs()->andReturn($tokenMock);
        });

        $this->mock(Request::class, function (MockInterface $mock) use ($userMock): void {
            $mock->shouldReceive('user')->once()->withNoArgs()->andReturn($userMock);
        });

        /** @var AuthController $authController */
        $authController = $this->app->make(abstract: AuthController::class);
        $token = $this->app->call([$authController, 'logout']);

        $this->assertInstanceOf(expected: PersonalAccessTokenResource::class, actual: $token);
    }
}
