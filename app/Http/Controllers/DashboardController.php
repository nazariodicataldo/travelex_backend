<?php

namespace App\Http\Controllers;

use App\Http\Resources\TravelPostResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\TravelPost;
use App\Models\User;
use App\Traits\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $authId = auth('sanctum')->id();

        $topPosts = TravelPost::withCount(['likes', 'comments'])
            ->with(['user', 'comments'])
            ->withExists([
                'likes as liked_by_me' => function ($q) use ($authId) {
                    $q->where('user_id', $authId);
                },
            ])
            ->orderByDesc('likes_count')
            ->orderByDesc('comments_count')
            ->limit(5)
            ->get();

        $topUsers = User::withCount(['comments', 'travelPosts', 'likes'])
            ->orderByDesc('comments_count')
            ->orderByDesc('likes_count')
            ->orderByDesc('travel_posts_count')
            ->limit(5)
            ->get();

        return $this->apiResponse(200, [
            'stats' => [
                'totalUsers' => User::count(),
                'totalPosts' => TravelPost::count(),
                'totalComments' => Comment::count(),
                'totalLikes' => Like::count(),
            ],
            'topPosts' => TravelPostResource::collection($topPosts),
            'topUsers' => UserResource::collection($topUsers),
        ]);
    }
}
