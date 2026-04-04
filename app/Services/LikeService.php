<?php

namespace App\Services;

use App\Http\Requests\StoreLikeRequest;
use App\Models\Like;
use App\Traits\ApiResponse;

class LikeService
{
    use ApiResponse;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store(StoreLikeRequest $request)
    {
        $data = $request->validated();

        return $this->apiResponse(true, Like::create($data));
    }
}
