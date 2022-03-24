<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Resources\Json\JsonResource;

final class BookDetailResource extends JsonResource
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'book_detail';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array<string, int|null|string>
     */
    final public function toArray($request)
    {
        /** @var \App\Models\BookDetail|static $this */
        return [
            'release_date' => $this->releaseDate,
            'description' => $this->description,
            'language' => $this->language,
            'country' => $this->country,
            'publisher' => $this->publisher,
            'page_count' => $this->pageCount,
            'category' => $this->category,
        ];
    }
}
