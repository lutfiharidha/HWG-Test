<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
{
     public function toArray($request)
    {
        return [
            'id' => $this->id,
            'borrow_date' => $this->borrow_date,
            'return_date' => $this->return_date,
            'actual_return_date' => $this->actual_return_date,
            'status' => $this->status,
            'book' => $this->book->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
