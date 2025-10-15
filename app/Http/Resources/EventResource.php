<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
                'title' => $this->title,
                'description' => $this->description,
                'date' => Carbon::parse($this->date)->format('Y-m-d'),
                'location' => $this->location,
                'available_seats' => $this->available_seats,
                'category' => new CategoryResource($this->category),
                'is_active' => $this->is_active == 1 ? "true" : "false",
                'created_at' => $this->created_at->diffForHumans(),
                'updated_at' => $this->updated_at->diffForHumans()
            ]
        ];
    }
}
