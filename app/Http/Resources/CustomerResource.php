<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'user'      => new UserResource($this->whenLoaded('user')),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'cart'      => new CartResource($this->whenLoaded('cart')),
            'wishlist'  => new WishlistResource($this->whenLoaded('wishlist')),
            'orders'    => OrderResource::collection($this->whenLoaded('orders')),
            'review'    => new ReviewResource($this->whenLoaded('review')),
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
}
