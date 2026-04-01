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
        try {
            $data = $request->validated();

            return $this->apiResponse(true, Comment::create($data));
        } catch (\Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while sending comment to post',
            );
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();

            return $this->apiResponse(true, null, 204);
        } catch (\Exception $e) {
            return $this->apiResponse(
                false,
                $e->getMessage(),
                $e->getCode(),
                'Error while deleting comment from the post',
            );
        }
    }
}
