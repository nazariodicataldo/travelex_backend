<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment' => $this->comment,
            'postId' => $this->travel_post_id,
            //'post' => $this->whenLoaded('post', new TravelPostResource($this->post)),
            'author' => $this->whenLoaded('user', new UserResource($this->user)),
        ];
    }
}
