<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function a_user_can_only_view_their_teams()
    {
        $this->withExceptionHandling()->signIn();

        $team2 = factory(Team::class)->create();

        $this->get('/teams/' . $team2->id)
            ->assertStatus(403);

        // Join the new team
        auth()->user()->joinTeam($team2);
        auth()->user()->load('teams');

        $this->get('/teams/' . $team2->id)
            ->assertSee($team2->name);
    }

    /** @test */
    function authenticated_user_can_view_their_teams()
    {
        $this->signIn();

        $teams = factory(Team::class, 15)->create();

        auth()->user()->joinTeam($teams[0]);
        auth()->user()->joinTeam($teams[14]);

        $this->get("/teams")
            ->assertSee($teams[0]->name)
            ->assertDontSee($teams[5]->name)
            ->assertSee($teams[14]->name);
    }
}
