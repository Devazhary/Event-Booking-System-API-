<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'event_info' => new EventResource($this->event),
            'user_info' => new UserResource($this->user),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
