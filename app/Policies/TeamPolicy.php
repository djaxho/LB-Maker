<?php

namespace App\Policies;

use App\User;
use App\team;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\User  $user
     * @param  \App\team  $team
     * @return mixed
     */
    public function view(User $user, team $team)
    {
        $authorize = false;

        foreach ($user->teams as $userTeam) {
            if($userTeam->id == $team->id) {
                $authorize = true;
            }
        }

        return $authorize;
    }

    /**
     * Determine whether the user can create teams.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param  \App\User  $user
     * @param  \App\team  $team
     * @return mixed
     */
    public function update(User $user, team $team)
    {
        foreach ($user->teams as $userTeam) {
            if($userTeam->id == $team->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param  \App\User  $user
     * @param  \App\team  $team
     * @return mixed
     */
    public function delete(User $user, team $team)
    {
        foreach ($user->teams as $userTeam) {
            if($userTeam->id == $team->id) {
                return true;
            }
        }

        return false;
    }
}
