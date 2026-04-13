<?php

namespace App\Http\Resources;

use App\Models\Like;
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
            'author' => $this->whenLoaded('user', new UserResource($this->user)),
            'likes' => $this->whenCounted('likes'),
            'commentsCount' => $this->whenCounted('comments'),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'likedByMe' => $this->when(
                //torna un boolean, se l'utente che fa la richiesta ha messo like al post
                auth('sanctum')->check(), //mi prendo l'utente da Request e verifico se sia diverso da null = autenticato
                fn() => Like::where('travel_post_id', $this->id)
                    ->where('user_id', auth('sanctum')->user()->id)
                    ->exists(), //mi carico i likes del post e verifico se esiste tra questi l'id dell'utente in questione
                false, //valore di default -> normalmente il post ha 0 likes
            ),
        ];
    }
}
