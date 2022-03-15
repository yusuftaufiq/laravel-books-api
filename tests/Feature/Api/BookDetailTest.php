<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class BookDetailTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithUser;

    private array $bookDetailStructure = [
        'release_date',
        'description',
        'language',
        'country',
        'publisher',
        'page_count',
        'category',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    public function testBookDetailIndex(): void
    {
        $response = $this->actingAs($this->user)->get(route('books.detail.index', ['book' => 1984]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => [
                'detail' => $this->bookDetailStructure,
            ],
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);
    }
}
