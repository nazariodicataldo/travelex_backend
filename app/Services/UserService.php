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
        return $this->apiResponse(true, new UserResource($user));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        return $this->apiResponse(true, new UserResource(User::create($data)));
    }
}
