<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithUser;

    private array $userStructure = [
        'name',
        'email',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    public function testShowCurrentUser()
    {
        $response = $this->actingAs($this->user)->get(route('user'));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'user' => $this->userStructure,
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);
    }

    public function testUnauthorizedShowCurrentUser()
    {
        $response = $this->get(route('user'));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_UNAUTHORIZED);
    }
}
