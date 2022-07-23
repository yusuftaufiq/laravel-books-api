<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\UserStructure;

class RegisterUserTest extends TestCase
{
    use ResourceAssertion;
    use ResourceStructure;
    use RefreshDatabase;
    use UserStructure;
    use WithFaker;

    /**
     * @test
     */
    public function itShouldReturnASuccessfulResponseIfTheGivenDataIsCorrect(): void
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $password = $this->faker->password(8);

        $response = $this->post(uri: route('register'), data: [
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
        $response->assertJsonPath(path: 'user', expect: [
            'name' => $name,
            'email' => $email,
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_CREATED);
        $this->assertDatabaseHas(table: 'users', data: [
            'name' => $name,
            'email' => $email,
        ]);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnprocessableResponseIfTheGivenDataIsIncorrect(): void
    {
        $response = $this->post(route('register'));

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
