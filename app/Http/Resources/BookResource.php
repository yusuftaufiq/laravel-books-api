<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'book';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var \App\Models\Book|static $this */
        return [
            'image' => $this->image,
            'title' => $this->title,
            'author' => $this->author,
            'price' => $this->price,
            'originalUrl' => $this->originalUrl,
            'url' => $this->url,
            'slug' => $this->slug,
            'detail' => $this->when($this->detail?->slug !== null, fn (): BookDetailResource => (
                new BookDetailResource($this->detail)
            )),
        ];
    }
}
