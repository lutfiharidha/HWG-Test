<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'publication_date' => $this->publication_date,
            'stock' => $this->stock,
            'category' => $this->category?$this->category->category_name:null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
