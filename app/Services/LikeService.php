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
        try {
            $data = $request->validated();

            return $this->apiResponse(true, Like::create($data));
        } catch (\Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while adding like to post',
            );
        }
    }
}
