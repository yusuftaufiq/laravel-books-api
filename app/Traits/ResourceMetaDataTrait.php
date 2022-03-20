<?php

namespace App\Traits;

use App\Support\HttpApiFormat;
use Symfony\Component\HttpFoundation\Response;

trait ResourceMetaDataTrait
{
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return (new HttpApiFormat(data: $this->with))->toArray();
    }

    /**
     * Customize the response for a request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\JsonResponse  $response
     * @return void
     */
    final public function withResponse($request, $response)
    {
        $statusCode = $response->getStatusCode();

        $this->with['status'] = $statusCode;
        $this->with['title'] = Response::$statusTexts[$statusCode];
    }
}
