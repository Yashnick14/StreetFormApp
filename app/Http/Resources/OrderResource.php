<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'customer_id' => $this->customer_id,
            'orderdate'   => $this->orderdate,
            'orderstatus' => $this->orderstatus,
            'totalprice'  => $this->totalprice,
            'customer'    => new CustomerResource($this->whenLoaded('customer')),
            'items'       => OrderItemResource::collection($this->whenLoaded('items')),
            'payment'     => new PaymentResource($this->whenLoaded('payment')),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
