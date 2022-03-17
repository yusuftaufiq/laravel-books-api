<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use ResourceAssertion;
    use ResourceStructure;
    use RefreshDatabase;
    use WithFaker;

    private array $userStructure = [
        'name',
        'email',
    ];

    public function testRegisterUser(): void
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $password = $this->faker->password(8);

        $response = $this->post(route('register'), [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'user' => $this->userStructure,
        ]);
        $response->assertJsonPath('user', [
            'name' => $name,
            'email' => $email,
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_CREATED);
        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function testUnprocessableRegisterUser(): void
    {
        $response = $this->post(route('register'));

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
