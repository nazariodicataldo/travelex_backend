<?php

namespace App\Services;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Traits\ApiResponse;

class CommentService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();

        return $this->apiResponse(true, Comment::create($data));
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return $this->apiResponse(true, null, 204);
    }
}
