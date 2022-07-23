<?php

namespace Tests\Feature\Api;

use App\Enums\CategoryEnum;
use App\Enums\LanguageEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\BookStructure;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class ShowAllBooksTest extends TestCase
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
    public function itShouldReturnASuccessfulResponseIfAuthenticated(): void
    {
        $response = $this->actingAs($this->user)->call(method: 'GET', uri: route('books.index'), parameters: [
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
        $response = $this->call(method: 'GET', uri: route('books.index'));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNAUTHORIZED);
    }
}
