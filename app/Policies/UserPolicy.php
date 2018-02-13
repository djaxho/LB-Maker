<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the authenticated user can view the user.
     *
     * @param  \App\User  $userAuthenticated
     * @param  \App\User  $userResource
     * @return mixed
     */
    public function view(User $userAuthenticated, User $userResource)
    {
        return $userAuthenticated->id == $userResource->id || $userAuthenticated->team()->id == $userResource->team()->id;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\User  $userAuthenticated
     * @param  \App\User  $userResource
     * @return mixed
     */
    public function update(User $userAuthenticated, User $userResource)
    {
        //
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\User  $userAuthenticated
     * @param  \App\User  $userResource
     * @return mixed
     */
    public function delete(User $userAuthenticated, User $userResource)
    {
        //
    }
}
