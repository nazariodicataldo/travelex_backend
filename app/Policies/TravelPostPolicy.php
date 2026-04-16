<?php

namespace App\Policies;

use App\Models\TravelPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TravelPostPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TravelPost $travelPost): Response
    {
        return $user->id === $travelPost->user_id
            ? Response::allow()
            : Response::deny("You can't edit another user's post");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TravelPost $travelPost): Response
    {
        return $user->id === $travelPost->user_id
            ? Response::allow()
            : Response::deny("You can't delete another user's post");
    }
}
