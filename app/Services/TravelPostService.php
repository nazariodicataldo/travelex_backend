<?php

namespace App\Services;

use App\Http\Requests\StoreTravelPostRequest;
use App\Http\Requests\UpdateTravelPostRequest;
use App\Http\Resources\TravelPostResource;
use App\Models\TravelPost;
use App\Traits\ApiResponse;

class TravelPostService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        try {
            return $this->apiResponse(true, TravelPostResource::collection(TravelPost::all()));
        } catch (\Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while fetching travel posts',
            );
        }
    }

    public function store(StoreTravelPostRequest $request)
    {
        try {
            $data = $request->validated();

            return $this->apiResponse(true, new TravelPostResource(TravelPost::create($data)), 201);
        } catch (\Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while creating travel post',
            );
        }
    }

    public function show(TravelPost $travel_post)
    {
        try {
            return $this->apiResponse(true, new TravelPostResource($travel_post));
        } catch (\Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while fetching travel post',
            );
        }
    }

    public function update(UpdateTravelPostRequest $request, TravelPost $travel_post)
    {
        try {
            $data = $request->validate();

            return $this->apiResponse(true, new TravelPostResource($travel_post->update($data)));
        } catch (\Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while updating travel post',
            );
        }
    }

    public function destroy(TravelPost $travel_post)
    {
        try {
            $travel_post->delete();

            return $this->apiResponse(true, null, 204);
        } catch (\Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while deleting travel post',
            );
        }
    }
}
