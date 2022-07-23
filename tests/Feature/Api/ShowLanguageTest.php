<?php

namespace Tests\Feature\Api;

use App\Enums\LanguageEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\LanguageStructure;
use Tests\ResourceAssertion;
use Tests\ResourceStructure;
use Tests\TestCase;
use Tests\WithUser;

class ShowLanguageTest extends TestCase
{
    use LanguageStructure;
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
        $response = $this->actingAs($this->user)->get(route(name: 'languages.show', parameters: [
            'language' => LanguageEnum::English->value,
        ]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'language' => $this->languageStructure,
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_OK);

        /** @var string $slug */
        $slug = $response->json('language.slug');

        $this->assertSlugs($slug);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnauthorizedResponseIfUnauthenticated(): void
    {
        $response = $this->get(route(name: 'languages.show', parameters: [
            'language' => LanguageEnum::English->value,
        ]));

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
        $response = $this->actingAs($this->user)->get(route(name: 'languages.show', parameters: [
            'language' => $this->faker->md5(),
        ]));

        $response->assertNotFound();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_NOT_FOUND);
    }
}
