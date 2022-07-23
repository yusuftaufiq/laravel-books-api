<?php

namespace Tests\Unit\Http\Controllers\AuthController;

use App\Contracts\UserInterface;
use App\Http\Controllers\Api\AuthController;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\PersonalAccessTokenResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery\MockInterface;
use Tests\TestCase;

class SimulateLoginRequestTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function itShouldBeInstanceOfPersonalAccessTokenResourceClassIfRequestSuccessful(): void
    {
        $tokenName = $this->faker->sentence;
        $expiredAt = Carbon::createFromInterface($this->faker->dateTimeBetween('today', '+1 month'));

        /** @var PersonalAccessToken $tokenMock */
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
            $mock->shouldReceive('authenticateOrFail')->once()->withNoArgs();
            $mock->shouldReceive('user')->once()->withNoArgs()->andReturn($userMock);
            $mock->shouldReceive('input')->once()->with('token_name')->andReturn($tokenName);
            $mock->shouldReceive('date')->once()->withSomeOfArgs('expired_at')->andReturn($expiredAt);
        });

        /** @var AuthController $authController */
        $authController = $this->app->make(abstract: AuthController::class);
        $token = $this->app->call([$authController, 'login']);

        $this->assertInstanceOf(expected: PersonalAccessTokenResource::class, actual: $token);
    }
}
