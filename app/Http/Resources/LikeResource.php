<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'post' => $this->whenLoaded('post', new TravelPostResource($this->post)),
            'user' => $this->whenLoaded('user', new UserResource($this->user)),
        ];
    }
}
