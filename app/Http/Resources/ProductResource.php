<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price ? (float) $this->sale_price : null,
            'current_price' => (float) $this->getCurrentPrice(),
            'sku' => $this->sku,
            'stock_quantity' => $this->stock_quantity,
            'in_stock' => $this->in_stock,
            'is_active' => $this->is_active,
            'images' => $this->images ?? [],
            'specifications' => $this->specifications ?? [],
            'brand' => $this->brand,
            'model' => $this->model,
            'weight' => $this->weight ? (float) $this->weight : null,
            'dimensions' => $this->dimensions,
            'is_on_sale' => $this->isOnSale(),
            'discount_percentage' => $this->getDiscountPercentage(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
