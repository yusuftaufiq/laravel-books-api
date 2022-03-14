<?php

namespace Tests\Api\Feature;

use App\Enums\CategoryEnum;
use App\Enums\LanguageEnum;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourcePattern;
use Tests\ResourceStructure;
use Tests\TestCase;

class BookTest extends TestCase
{
    use ResourcePattern;
    use ResourceStructure;
    use WithFaker;

    private array $bookStructure = [
        'image',
        'title',
        'author',
        'price',
        'originalUrl',
        'url',
        'slug',
    ];

    public function testBookIndex(): void
    {
        $response = $this->call('GET', route('books.index'), [
            'category' => CategoryEnum::HistoricalFiction->value,
            'language' => LanguageEnum::English->value,
        ]);

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

    public function testBookShow(): void
    {
        $response = $this->get(route('books.show', ['book' => 1984]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);
        $this->assertSlugs($response->json('book.slug'));
    }

    public function testBookNotFound(): void
    {
        $response = $this->get(route('books.show', ['book' => $this->faker->md5()]));

        $response->assertNotFound();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_NOT_FOUND);
    }
}