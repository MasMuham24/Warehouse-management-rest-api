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
            'category_id' => ['id' => $this->category?->id, 'name' => $this->supplier?->name],
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'stock' =>  $this->stock,
            'minimum_stock' => $this->minimum_stock,
            'stock_status' => $this->stock <= $this->minimum_stock ? 'low_stock' : 'in_stock',
            'created_at' => $this->created_at?->toISOString(),
            'update_at' => $this->update_at?->toISOString(), 
        ];
    }
}
