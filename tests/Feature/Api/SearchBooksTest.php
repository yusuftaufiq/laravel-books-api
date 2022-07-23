<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\BookStructure;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class SearchBooksTest extends TestCase
{
    use BookStructure;
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    /**
     * @test
     */
    public function itShouldReturnASuccessfulResponseIfAuthenticated(): void
    {
        $response = $this->actingAs($this->user)->call(method: 'GET', uri: route('books.search'), parameters: [
            'keyword' => 1984,
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            ...$this->paginationStructure,
            ...$this->resourceMetaDataStructure,
            'books' => [
                '*' => collect($this->bookStructure)->diff(['price'])->values()->toArray(),
            ],
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_OK);

        /** @var array $slugs */
        $slugs = $response->json('books.*.slug');

        $this->assertSlugs(...$slugs);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnauthorizedResponseIfUnauthenticated(): void
    {
        $response = $this->get(route(name: 'books.search', parameters: ['keyword' => 1984]));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNAUTHORIZED);
    }
}
