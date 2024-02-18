<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_name' => $this->category_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'books' => BookResource::collection($this->books),
        ];
    }
}
