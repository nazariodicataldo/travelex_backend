<?php

namespace App\Services;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;

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
        try {
            return $this->apiResponse(true, new UserResource($user));
        } catch (Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while fetching user',
            );
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            return $this->apiResponse(true, new UserResource(User::create($data)));
        } catch (Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while creating user',
            );
        }
    }
}
