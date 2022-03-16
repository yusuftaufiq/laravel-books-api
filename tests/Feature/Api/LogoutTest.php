<?php

namespace Tests\Feature\Api;

use App\Enums\TokenStatusEnum;
use Database\Factories\PersonalAccessTokenFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class LogoutTest extends TestCase
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
        'status',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    public function testLogoutUser()
    {
        /** @var \App\Models\PersonalAccessToken */
        $token = $this->user->tokens()->firstOrNew();

        $this->assertDatabaseCount(table: 'personal_access_tokens', count: 1);

        $response = $this->withHeader(
            name: 'Authorization',
            value: 'Bearer ' . $token->id . '|' . PersonalAccessTokenFactory::DEFAULT_PLAIN_TEXT_TOKEN,
        )->post(uri: route('logout'));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'token' => $this->tokenStructure,
        ]);

        $response->assertJson(fn (AssertableJson $json) => (
            $json
                ->where(key: 'token.name', expected: $token->name)
                ->whereContains(key: 'token.abilities', expected: '*')
                ->where(key: 'token.type', expected: 'Bearer')
                ->where(key: 'token.status', expected: TokenStatusEnum::Revoked->value)
                ->etc()
        ));

        $this->assertResourceMetaData($response, statusCode: Response::HTTP_OK);
        $this->assertDatabaseCount(table: 'personal_access_tokens', count: 0);
    }

    public function testUnauthorizedLogoutUser()
    {
        $response = $this->post(uri: route('logout'));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, statusCode: Response::HTTP_UNAUTHORIZED);
    }
}
