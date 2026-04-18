<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelPostRequest;
use App\Http\Requests\UpdateTravelPostRequest;
use App\Models\TravelPost;
use App\Services\TravelPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TravelPostController extends Controller
{
    public function __construct(private TravelPostService $travelPostService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->travelPostService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTravelPostRequest $request)
    {
        return $this->travelPostService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelPost $travel_post)
    {
        return $this->travelPostService->show($travel_post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTravelPostRequest $request, TravelPost $travel_post)
    {
        Gate::authorize('update', $travel_post);
        return $this->travelPostService->update($request, $travel_post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelPost $travel_post)
    {
        Gate::authorize('delete', $travel_post);
        return $this->travelPostService->destroy($travel_post);
    }
}
