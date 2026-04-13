<?php

namespace App\Services;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

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

        //creo il commento a partire dall'utente
        $comment = Auth::user()->comments()->create($data);

        return $this->apiResponse(true, $comment);
    }

    public function destroy(Comment $comment_model)
    {
        $comment_model->delete();

        return $this->apiResponse(true, 'Comment deleted', 200);
    }
}
