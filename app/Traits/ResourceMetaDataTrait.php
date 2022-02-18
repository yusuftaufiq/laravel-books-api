<?php

namespace App\Traits;

use App\Support\HttpApiFormat;

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
        return (new HttpApiFormat())->toArray();
    }
}
