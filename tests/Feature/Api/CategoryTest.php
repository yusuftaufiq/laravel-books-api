<?php

namespace Tests\Feature\Api;

use App\Enums\CategoryEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithFaker;
    use WithUser;

    private array $categoryStructure = [
        'slug',
        'name',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    public function testCategoryIndex(): void
    {
        $response = $this->actingAs($this->user)->get(route('categories.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'categories' => [
                '*' => $this->categoryStructure,
            ],
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);

        /** @var array */
        $slugs = $response->json('categories.*.slug');

        $this->assertSlugs(...$slugs);
    }

    public function testShowCategory(): void
    {
        $response = $this->actingAs($this->user)->get(route('categories.show', [
            'category' => CategoryEnum::HistoricalFiction->value,
        ]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'category' => $this->categoryStructure,
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);

        /** @var string */
        $slug = $response->json('category.slug');

        $this->assertSlugs($slug);
    }

    public function testUnauthorizedShowCategory(): void
    {
        $response = $this->get(route('categories.show', [
            'category' => CategoryEnum::HistoricalFiction->value,
        ]));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_UNAUTHORIZED);
    }

    public function testCategoryNotFound(): void
    {
        $response = $this->actingAs($this->user)->get(route('categories.show', ['category' => $this->faker->md5()]));

        $response->assertNotFound();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_NOT_FOUND);
    }
}
