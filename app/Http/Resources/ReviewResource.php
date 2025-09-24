<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'customer_id'=> $this->customer_id,
            'product_id' => $this->product_id,
            'title'      => $this->title,
            'comments'   => $this->comments,
            'rating'     => $this->rating,
            'customer'   => new CustomerResource($this->whenLoaded('customer')),
            'product'    => new ProductResource($this->whenLoaded('product')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
