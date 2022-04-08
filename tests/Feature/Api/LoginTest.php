<?php

namespace Tests\Feature\Api;

use App\Enums\TokenStatusEnum;
use App\Http\Requests\LoginRequest;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithFaker;
    use WithUser;

    private array $tokenStructure = [
        'name',
        'abilities',
        'expired_at',
        'type',
        'plain_text',
        'status',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    public function testLoginUser(): void
    {
        /** @var PersonalAccessToken */
        $token = PersonalAccessToken::factory()->make();
        $expiredAt = $token->expired_at instanceof Carbon ? $token->expired_at->format('Y-m-d') : '';

        $response = $this->post(uri: route('login'), data: [
            'email' => $this->user->email,
            'password' => UserFactory::DEFAULT_PLAIN_TEXT_PASSWORD,
            'token_name' => $token->name,
            'expired_at' => $expiredAt,
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'token' => $this->tokenStructure,
        ]);

        $response->assertJson(fn (AssertableJson $json): AssertableJson => (
            $json
                ->where(key: 'token.name', expected: $token->name)
                ->whereContains(key: 'token.abilities', expected: '*')
                ->where(key: 'token.type', expected: 'Bearer')
                ->where(key: 'token.status', expected: TokenStatusEnum::Active->value)
                ->etc()
        ));

        /** @var string */
        $actualExpiredAt = $response->json('token.expired_at');

        $this->assertStringContainsString(
            needle: $expiredAt,
            haystack: $actualExpiredAt,
        );
        $this->assertResourceMetaData($response, statusCode: Response::HTTP_CREATED);
        $this->assertDatabaseHas(table: 'personal_access_tokens', data: [
            'tokenable_type' => User::class,
            'tokenable_id' => $this->user->id,
            'name' => $token->name,
        ]);
    }

    public function testUserNotExist(): void
    {
        $response = $this->post(uri: route('login'), data: [
            'email' => $this->faker->email,
            'password' => $this->faker->randomAscii,
            'token_name' => $this->faker->word,
            'expired_at' => $this->faker->dateTimeBetween('today', '+1 month')->format('Y-m-d'),
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUnprocessableLoginUser(): void
    {
        $response = $this->post(uri: route('login'));

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testTooManyRequests(): void
    {
        /** @var RateLimiter */
        $rateLimiter = $this->app->make(abstract: RateLimiter::class);
        $throttleKey = \Str::lower("{$this->user->email}|") . \Request::ip();

        collect(range(1, LoginRequest::MAX_ATTEMPTS))->each(function () use ($rateLimiter, $throttleKey): void {
            $this->app->call(callback: [$rateLimiter, 'hit'], parameters: ['key' => $throttleKey]);
        });

        /** @var PersonalAccessToken */
        $token = PersonalAccessToken::factory()->make();
        $expiredAt = $token->expired_at instanceof Carbon ? $token->expired_at->format('Y-m-d') : '';

        $response = $this->post(uri: route('login'), data: [
            'email' => $this->user->email,
            'password' => UserFactory::DEFAULT_PLAIN_TEXT_PASSWORD,
            'token_name' => $token->name,
            'expired_at' => $expiredAt,
        ]);

        $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, statusCode: Response::HTTP_TOO_MANY_REQUESTS);
    }
}
