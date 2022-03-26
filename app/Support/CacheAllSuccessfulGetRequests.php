<?php

namespace App\Support;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests as CacheProfilesCacheAllSuccessfulGetRequests;

class CacheAllSuccessfulGetRequests extends CacheProfilesCacheAllSuccessfulGetRequests
{
    public function useCacheNameSuffix(Request $request): string
    {
        return '';
    }
}
