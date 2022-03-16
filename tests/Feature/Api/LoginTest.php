<?php

namespace Tests\Feature\Api;

use App\Enums\TokenStatusEnum;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function testLoginUser()
    {
        $tokenName = $this->faker->sentence;
        $expiredAt = $this->faker->dateTimeBetween('today', '+1 month')->format('Y-m-d');

        $response = $this->post(uri: route('login'), data: [
            'email' => $this->user->email,
            'password' => UserFactory::DEFAULT_PLAIN_TEXT_PASSWORD,
            'token_name' => $tokenName,
            'expired_at' => $expiredAt,
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'token' => $this->tokenStructure,
        ]);

        $response->assertJson(fn (AssertableJson $json) => (
            $json
                ->where(key: 'token.name', expected: $tokenName)
                ->whereContains(key: 'token.abilities', expected: '*')
                ->where(key: 'token.type', expected: 'Bearer')
                ->where(key: 'token.status', expected: TokenStatusEnum::Active->value)
                ->etc()
        ));

        $this->assertStringContainsString(needle: $expiredAt, haystack: $response->json('token.expired_at'));
        $this->assertResourceMetaData($response, statusCode: Response::HTTP_CREATED);
        $this->assertDatabaseHas(table: 'personal_access_tokens', data: [
            'tokenable_type' => User::class,
            'tokenable_id' => $this->user->id,
            'name' => $tokenName,
        ]);
    }

    public function testUnprocessableLoginUser()
    {
        $response = $this->post(uri: route('login'));

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
