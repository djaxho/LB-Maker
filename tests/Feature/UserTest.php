<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function a_user_has_a_profile()
    {
        $this->signIn();

        $this->get('/users/' . auth()->id())
            ->assertSee(auth()->user()->name);
    }

    /** @test */
    function a_user_can_only_access_own_and_teams_profiles()
    {
        $this->withExceptionHandling()->signIn();

        $user2 = factory(User::class)->create();
        $user2->joinTeam(factory(Team::class)->create());

        // see own profile
        $this->get('/users/' . auth()->id())
            ->assertSee(auth()->user()->name);

        // cannot see non-team-member profile
        $this->get('/users/' . $user2->id)
            ->assertStatus(403);

        $user2->joinTeam(auth()->user()->team());

        // Can see team-member profile
        $this->get('/users/' . $user2->id)
            ->assertSee($user2->name);
    }
}
