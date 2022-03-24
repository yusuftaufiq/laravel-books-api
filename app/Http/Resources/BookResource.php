<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Resources\Json\JsonResource;

final class BookResource extends JsonResource
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
     *
     * @return array<string, mixed>
     */
    final public function toArray($request)
    {
        /** @var \App\Models\Book|static $this */
        return [
            'image' => $this->image,
            'title' => $this->title,
            'author' => $this->author,
            'price' => $this->when($this->price !== null, $this->price),
            'original_url' => $this->originalUrl,
            'url' => $this->url,
            'slug' => $this->slug,
            'detail' => $this->when($this->detail?->slug !== null, fn (): BookDetailResource => (
                new BookDetailResource($this->detail)
            )),
        ];
    }
}
