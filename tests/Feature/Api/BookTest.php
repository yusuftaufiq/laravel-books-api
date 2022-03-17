<?php

namespace Tests\Api\Feature;

use App\Enums\CategoryEnum;
use App\Enums\LanguageEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class BookTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithFaker;
    use WithUser;

    private array $bookStructure = [
        'image',
        'title',
        'author',
        'price',
        'original_url',
        'url',
        'slug',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    private function assertBooksStructure(TestResponse $response)
    {
        $response->assertJsonStructure([
            ...$this->paginationStructure,
            ...$this->resourceMetaDataStructure,
            'books' => [
                '*' => $this->bookStructure,
            ],
        ]);
    }

    public function testBookIndex(): void
    {
        $response = $this->actingAs($this->user)->call(method: 'GET', uri: route('books.index'), parameters: [
            'category' => CategoryEnum::HistoricalFiction->value,
            'language' => LanguageEnum::English->value,
        ]);

        $response->assertOk();

        $this->assertBooksStructure($response);
        $this->assertResourceMetaData($response, Response::HTTP_OK);
        $this->assertSlugs(...$response->json('books.*.slug'));
    }

    public function testBookIndexByCategory(): void
    {
        $response = $this->actingAs($this->user)->get(route('categories.books.index', [
            'category' => CategoryEnum::HistoricalFiction->value,
        ]));

        $response->assertOk();

        $this->assertBooksStructure($response);
        $this->assertResourceMetaData($response, Response::HTTP_OK);
        $this->assertSlugs(...$response->json('books.*.slug'));
    }

    public function testBookIndexByLanguage(): void
    {
        $response = $this->actingAs($this->user)->get(route('languages.books.index', [
            'language' => LanguageEnum::English->value,
        ]));

        $response->assertOk();

        $this->assertBooksStructure($response);
        $this->assertResourceMetaData($response, Response::HTTP_OK);
        $this->assertSlugs(...$response->json('books.*.slug'));
    }

    public function testShowBook(): void
    {
        $response = $this->actingAs($this->user)->get(route('books.show', ['book' => 1984]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);
        $this->assertSlugs($response->json('book.slug'));
    }

    public function testUnauthorizedShowBook(): void
    {
        $response = $this->get(route('books.show', ['book' => 1984]));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_UNAUTHORIZED);
    }

    public function testBookNotFound(): void
    {
        $response = $this->actingAs($this->user)->get(route('books.show', ['book' => $this->faker->md5()]));

        $response->assertNotFound();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_NOT_FOUND);
    }
}
