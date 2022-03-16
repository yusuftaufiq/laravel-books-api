<?php

namespace Tests\Feature\Api;

use App\Enums\TokenStatusEnum;
use App\Models\PersonalAccessToken;
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
        /** @var PersonalAccessToken */
        $token = PersonalAccessToken::factory()->make();

        $response = $this->post(uri: route('login'), data: [
            'email' => $this->user->email,
            'password' => UserFactory::DEFAULT_PLAIN_TEXT_PASSWORD,
            'token_name' => $token->name,
            'expired_at' => $token->expired_at->format('Y-m-d'),
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'token' => $this->tokenStructure,
        ]);

        $response->assertJson(fn (AssertableJson $json) => (
            $json
                ->where(key: 'token.name', expected: $token->name)
                ->whereContains(key: 'token.abilities', expected: '*')
                ->where(key: 'token.type', expected: 'Bearer')
                ->where(key: 'token.status', expected: TokenStatusEnum::Active->value)
                ->etc()
        ));

        $this->assertStringContainsString(needle: $token->expired_at, haystack: $response->json('token.expired_at'));
        $this->assertResourceMetaData($response, statusCode: Response::HTTP_CREATED);
        $this->assertDatabaseHas(table: 'personal_access_tokens', data: [
            'tokenable_type' => User::class,
            'tokenable_id' => $this->user->id,
            'name' => $token->name,
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
