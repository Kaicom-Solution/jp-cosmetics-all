<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $attributeImages = [];

        $this->attributes->each(function ($attr) use (&$attributeImages) {
            $attr->attribute_images->each(function ($img) use (&$attributeImages, $attr) {
                $attributeImages[] = [
                    'attribute_id' => $attr->id,
                    'image'        => $img->image_path,
                ];
            });
        });

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'primary_image' => $this->primary_image,
            'product_type'  => $this->product_type,
            'status'            => $this->status,
            'short_description' => $this->short_description,
            'long_description'  => $this->long_description,
            'ingredients'       => $this->ingredients,
            'how_to_use'        => $this->how_to_use,

            'category' => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug ?? null,
            ],

            'brand' => $this->brand ? [
                'id'   => $this->brand->id,
                'name' => $this->brand->name,
            ] : null,

            'attributes' => ProductAttributeResource::collection($this->attributes),

            'attribute_images' => $attributeImages,
            'reviews' => $this->approved_reviews->map(function ($detail) {
                return [
                    'customer_name' => $detail->order->customer->name ?? 'Anonymous',
                    'rating'        => $detail->rating,
                    'review'        => $detail->review,
                ];
            }),
        ];
    }
}