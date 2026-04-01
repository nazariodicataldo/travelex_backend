<?php

namespace App\Http\Resources;

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
            'name' => $this->name,
            'email' => $this->email,
            'posts' => $this->whenLoaded('posts', function ($posts) {
                return $posts->map(
                    fn($post) => [
                        'id' => $post->id,
                        'title' => $post->title,
                        'description' => $post->description,
                        'country' => $post->country,
                    ],
                );
            }),
        ];
    }
}
