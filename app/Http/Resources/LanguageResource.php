<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Resources\Json\JsonResource;

final class LanguageResource extends JsonResource
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'language';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array<string, (int|string|null)>
     */
    public function toArray($request)
    {
        /** @var \App\Models\Language|static $this */
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
