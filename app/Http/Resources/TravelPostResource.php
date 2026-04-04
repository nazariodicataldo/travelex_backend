<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelPostResource extends JsonResource
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
            'location' => $this->location,
            'slug' => $this->slug,
            'description' => $this->description,
            'country' => $this->country,
            'img' => $this->img,
            'countLike' => $this->whenCounted('post'),
            'author' => $this->whenLoaded('user', new UserResource($this->user)),
            'likes' => $this->whenCounted('likes'),
            'comments' => $this->whenLoaded('comments'),
        ];
    }
}
