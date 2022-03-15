<?php

namespace Tests\Feature\Api;

use App\Enums\LanguageEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class LanguageTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithFaker;
    use WithUser;

    private array $languageStructure = [
        'slug',
        'name',
        'value',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    public function testLanguageIndex()
    {
        $response = $this->actingAs($this->user)->get(route('languages.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'languages' => [
                '*' => $this->languageStructure,
            ],
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);
        $this->assertSlugs(...$response->json('languages.*.slug'));
    }

    public function testShowLanguage()
    {
        $response = $this->actingAs($this->user)->get(route('languages.show', [
            'language' => LanguageEnum::English->value,
        ]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'language' => $this->languageStructure,
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_OK);
        $this->assertSlugs($response->json('language.slug'));
    }

    public function testUnauthorizedShowLanguage()
    {
        $response = $this->get(route('languages.show', [
            'language' => LanguageEnum::English->value,
        ]));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_UNAUTHORIZED);
    }

    public function testLanguageNotFound(): void
    {
        $response = $this->actingAs($this->user)->get(route('languages.show', ['language' => $this->faker->md5()]));

        $response->assertNotFound();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData($response, Response::HTTP_NOT_FOUND);
    }
}
