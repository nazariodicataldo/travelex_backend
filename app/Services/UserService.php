<?php

namespace App\Services;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;

class UserService
{
    use ApiResponse;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function show(User $user)
    {
        $authId = auth('sanctum')->id();

        $user->load([
            // Carichiamo i post dell'utente
            'travelPosts' => function ($query) use ($authId) {
                $query
                    ->with('user') // Carica l'autore del post (Eager Loading)
                    ->withExists([
                        'likes as liked_by_me' => function ($q) use ($authId) {
                            $q->where('user_id', $authId); // Verifica se IO ho messo like
                        },
                    ]);
            },
            // Carichiamo i post che l'utente ha messo mi piace
            'likes.travelPost' => function ($query) use ($authId) {
                $query->with('user')->withExists([
                    'likes as liked_by_me' => function ($q) use ($authId) {
                        $q->where('user_id', $authId);
                    },
                ]);
            },
        ]);

        return $this->apiResponse(true, new UserResource($user));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        return $this->apiResponse(true, new UserResource(User::create($data)));
    }
}
