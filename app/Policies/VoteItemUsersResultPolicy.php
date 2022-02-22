<?php

namespace App\Policies;

use App\User;
use App\VoteItemUsersResult;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoteItemUsersResultPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the vote item users result.
     *
     * @param  \App\User  $user
     * @param  \App\VoteItemUsersResult  $voteItemUsersResult
     * @return mixed
     */
    public function view(User $user, VoteItemUsersResult $voteItemUsersResult)
    {
    }

    /**
     * Determine whether the user can create vote item users results.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->id == auth()->id();
    }

    /**
     * Determine whether the user can update the vote item users result.
     *
     * @param  \App\User  $user
     * @param  \App\VoteItemUsersResult  $voteItemUsersResult
     * @return mixed
     */
    public function update(User $user, VoteItemUsersResult $voteItemUsersResult)
    {
        //
    }

    /**
     * Determine whether the user can delete the vote item users result.
     *
     * @param  \App\User  $user
     * @param  \App\VoteItemUsersResult  $voteItemUsersResult
     * @return mixed
     */
    public function delete(User $user, VoteItemUsersResult $voteItemUsersResult)
    {
        //
    }

    /**
     * Determine whether the user can restore the vote item users result.
     *
     * @param  \App\User  $user
     * @param  \App\VoteItemUsersResult  $voteItemUsersResult
     * @return mixed
     */
    public function restore(User $user, VoteItemUsersResult $voteItemUsersResult)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the vote item users result.
     *
     * @param  \App\User  $user
     * @param  \App\VoteItemUsersResult  $voteItemUsersResult
     * @return mixed
     */
    public function forceDelete(User $user, VoteItemUsersResult $voteItemUsersResult)
    {
        //
    }
}
