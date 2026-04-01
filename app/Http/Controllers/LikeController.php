<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Services\LikeService;

class LikeController extends Controller
{
    public function __construct(private LikeService $likeService) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLikeRequest $request)
    {
        return $this->likeService->store($request);
    }
}
