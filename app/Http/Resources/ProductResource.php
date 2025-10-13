<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'sizes' => $this->stockquantity ?? [],
            'price'         => $this->price,
            'type'          => $this->type,
            'image'   => $this->image  ? asset('storage/'.$this->image)  : null,
            'image2'  => $this->image2 ? asset('storage/'.$this->image2) : null,
            'image3'  => $this->image3 ? asset('storage/'.$this->image3) : null,
            'image4'  => $this->image4 ? asset('storage/'.$this->image4) : null,  
            'category'      => $this->whenLoaded('category', fn () => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ]),
            'cart_items'     => CartItemResource::collection($this->whenLoaded('cartItems')),
            'wishlist_items' => WishlistItemResource::collection($this->whenLoaded('wishlistItems')),
            'order_items'    => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
