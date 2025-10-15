<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Success',
            'data' => [
                'id' => $this->id,
                'name' => $this->name . ', number of events: ' . $this->events->count(),
                'is_active' => $this->is_active == 1 ? "true" : "false",
                'created_at' => $this->created_at->diffForHumans(),
                'updated_at' => $this->updated_at->diffForHumans()
            ]
        ];
    }
}
