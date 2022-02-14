<?php

namespace App\Support;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Phpro\ApiProblem\Http\HttpApiProblem;

class HttpApiFormat extends HttpApiProblem
{
    protected Collection $data;

    public function __construct(int $statusCode = Response::HTTP_OK, array $data = [])
    {
        $this->data = collect([
            'status' => $statusCode,
            'type' => static::TYPE_HTTP_RFC,
            'title' => static::getTitleForStatusCode($statusCode),
        ])->merge($data);
    }

    public static function getTitleForStatusCode(int $statusCode): string
    {
        return Response::$statusTexts[$statusCode] ?? 'Unknown';
    }

    public function toArray(): array
    {
        return $this->data->toArray();
    }
}
