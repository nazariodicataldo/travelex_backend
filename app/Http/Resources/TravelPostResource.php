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
            'title' => $this->title,
            'description' => $this->description,
            'country' => $this->country,
            'author' => $this->whenLoaded('user', function ($user) {
                return [
                    'name' => $user->full_name,
                    'email' => $user->email,
                ];
            }),
            'likes' => $this->whenCounted('likes'),
            'comments' => $this->whenLoaded('comments'),
        ];
    }
}
