<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResourceAssertion
{
    private function assertSlugs(string ...$slugs): void
    {
        collect($slugs)->each(function (string $slug) {
            /** @var \Tests\TestCase $this */
            $this->assertMatchesRegularExpression(pattern: '/^[a-z0-9-\']+$/', string: $slug);
        });
    }

    private function assertResourceMetaData(TestResponse $response, int $statusCode): void
    {
        $response->assertJson([
            'status' => $statusCode,
            'title' => Response::$statusTexts[$statusCode],
        ]);
    }
}
