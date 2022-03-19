<?php

namespace Tests\Unit\Http\Controllers;

use App\Contracts\UserInterface;
use App\Http\Controllers\Api\AuthController;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\PersonalAccessTokenResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker;

    public function testLoginUser(): void
    {
        $tokenName = $this->faker->sentence;
        $expiredAt = $this->faker->dateTimeBetween('today', '+1 month')->format('Y-m-d');

        $tokenMock = \Mockery::mock(PersonalAccessToken::class);
        $userMock = \Mockery::mock(UserInterface::class, function (MockInterface $mock) use (
            $tokenName,
            $expiredAt,
            $tokenMock,
        ): void {
            $mock->shouldReceive('createExpirableToken')
                ->once()
                ->with($tokenName, $expiredAt)
                ->andReturn(new NewAccessToken($tokenMock, $this->faker->md5));
        });

        $this->mock(LoginRequest::class, function (MockInterface $mock) use (
            $tokenName,
            $expiredAt,
            $userMock,
        ): void {
            $mock->shouldReceive('authenticateOrFail')->once()->withNoArgs()->andReturn();
            $mock->shouldReceive('user')->once()->withNoArgs()->andReturn($userMock);
            $mock->shouldReceive('get')->once()->with('token_name')->andReturn($tokenName);
            $mock->shouldReceive('get')->once()->with('expired_at')->andReturn($expiredAt);
        });

        /** @var AuthController */
        $authController = $this->app->make(abstract: AuthController::class);
        $token = $this->app->call([$authController, 'login']);

        $this->assertInstanceOf(expected: PersonalAccessTokenResource::class, actual: $token);
    }

    public function testLogoutUser(): void
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

        /** @var AuthController */
        $authController = $this->app->make(abstract: AuthController::class);
        $token = $this->app->call([$authController, 'logout']);

        $this->assertInstanceOf(expected: PersonalAccessTokenResource::class, actual: $token);
    }
}
