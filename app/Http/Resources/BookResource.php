<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'cover_image' => asset('storage/cover_images/'.$this->cover_image),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
          ];
    }
}
