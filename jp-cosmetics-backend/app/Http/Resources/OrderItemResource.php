<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => new ProductMiniResource($this->whenLoaded('product')),

            'product_attribute' => new ProductAttributeMiniResource(
                $this->whenLoaded('productAttribute')
            ),

            'quantity'        => $this->quantity,
            'unit_price'      => number_format($this->unit_price),
            'sub_total'       => $this->sub_total,
            'discount_amount' => $this->discount_amount,
            'payable'         => $this->payable,

            //fetch data when work
            'coupon' => null,
        ];
    }
}
