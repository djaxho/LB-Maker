<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

abstract class BasicAuthModelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public function update(User $user, Model $model)
    {
        return $model->user_id == $user->id || $model->team_id == $user->team()->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public function delete(User $user, Model $model)
    {
        return $model->user_id == $user->id;
    }

}