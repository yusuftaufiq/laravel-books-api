<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Resources\Json\JsonResource;

final class PersonalAccessTokenResource extends JsonResource
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'token';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array<string, mixed>
     */
    final public function toArray($request)
    {
        /** @var \App\Models\PersonalAccessToken|static $this */
        return [
            'name' => $this->name,
            'abilities' => $this->abilities,
            'expired_at' => $this->expired_at,
        ];
    }
}
