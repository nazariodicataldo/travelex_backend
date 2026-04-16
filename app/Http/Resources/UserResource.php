<?php

namespace App\Http\Resources;

/* use App\Models\TravelPost; */
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'posts' => TravelPostResource::collection($this->whenLoaded('travelPosts')),
        ];
    }
}
