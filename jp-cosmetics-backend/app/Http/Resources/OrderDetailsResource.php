<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentActivity = $this->activities->first();

        return [
            'id' => $this->id,
            'order' => [
                'order_number'     => $this->order_number,
                'sub_total_amount' => $this->sub_total_amount,
                'delivery_charge'  => $this->delivery_charge,
                'discount_amount'  => $this->discount_amount,
                'payable_total'    => $this->payable_total,

                'payment_status'   => $this->payment_status,
                'payment_method'   => $this->payment_method,
                'transaction_id'   => $this->transaction_id,

                'order_status'     => $this->status,
                'current_status'   => $currentActivity?->to_status ?? $this->status,
                'last_update_at'   => $currentActivity?->created_at ?? $this->updated_at,

                'receiver' => [
                    'name'  => $this->receiver_name,
                    'email' => $this->receiver_email,
                    'phone' => $this->receiver_phone,
                ],

                'shipping' => [
                    'city'     => $this->shipping_city,
                    'area'     => $this->shipping_area,
                    'location' => $this->shipping_location,
                ],

                'address'    => new AddressResource($this->whenLoaded('address')),
                'order_note' => $this->order_note,
                'remarks'    => $this->remarks,
                'created_at' => $this->created_at,
            ],


            'items'    => OrderItemResource::collection($this->whenLoaded('details')),
            'timeline' => OrderTimelineResource::collection($this->whenLoaded('activities')),
        ];
    }
}
