<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class SearchBookTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithUser;

    private array $bookStructure = [
        'image',
        'title',
        'author',
        'original_url',
        'url',
        'slug',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    public function testSearchBook(): void
    {
        $response = $this->actingAs($this->user)->get(route('books.search', ['keyword' => 1984]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->paginationStructure,
            ...$this->resourceMetaDataStructure,
            'books' => [
                '*' => $this->bookStructure,
            ],
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);
        $this->assertSlugs(...$response->json('books.*.slug'));
    }

    public function testUnauthorizedSearchBook(): void
    {
        $response = $this->get(route('books.search', ['keyword' => 1984]));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_UNAUTHORIZED);
    }
}
