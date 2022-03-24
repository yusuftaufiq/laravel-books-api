<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'user';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array<string, string>
     */
    final public function toArray($request)
    {
        /** @var \App\Models\User|static $this */
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
