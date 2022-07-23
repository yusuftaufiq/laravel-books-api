<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\BookStructure;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class ShowBookTest extends TestCase
{
    use BookStructure;
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithFaker;
    use WithUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    /**
     * @test
     */
    public function itShouldReturnASuccessfulResponseIfAuthenticatedAndResourceAvailable(): void
    {
        $response = $this->actingAs($this->user)->get(route(name: 'books.show', parameters: ['book' => 1984]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_OK);

        /** @var string $slug */
        $slug = $response->json('book.slug');

        $this->assertSlugs($slug);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnauthorizedResponseIfUnauthenticated(): void
    {
        $response = $this->get(route(name: 'books.show', parameters: ['book' => 1984]));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function itShouldReturnANotFoundResponseIfResourceNotAvailable(): void
    {
        $response = $this->actingAs($this->user)->get(route(name: 'books.show', parameters: [
            'book' => $this->faker->md5()
        ]));

        $response->assertNotFound();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_NOT_FOUND);
    }
}
