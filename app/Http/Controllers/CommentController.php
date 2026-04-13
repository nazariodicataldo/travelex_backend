<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request) {
        return $this->commentService->store($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment_model)
    {
        return $this->commentService->destroy($comment_model);
    }
}
