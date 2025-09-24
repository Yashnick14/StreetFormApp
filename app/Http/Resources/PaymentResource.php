<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->_id,
            'order_id'      => $this->order_id,
            'paymentmethod' => $this->paymentmethod,
            'amount'        => $this->amount,
            'paymentdate'   => $this->paymentdate,
            'order'         => new OrderResource($this->whenLoaded('order')),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
