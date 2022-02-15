<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var \App\Models\BookDetail|BookDetailResource $this */
        return [
            'releaseDate' => $this->releaseDate,
            'description' => $this->description,
            'language' => $this->language,
            'country' => $this->country,
            'publisher' => $this->publisher,
            'pageCount' => $this->pageCount,
            'category' => $this->category,
        ];
    }
}
