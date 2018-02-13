<?php

namespace Tests\Feature;

use App\Blog;
use App\BlogGroup;
use App\Leadbox;
use App\Team;
use App\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_team_can_have_a_user_added()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();

        $team->load('users');
        $this->assertEmpty($team->users);

        $team->addUser($user);

        $team->load('users');
        $this->assertTrue($team->users->contains($user));
    }

    /** @test */
    public function a_team_can_have_a_user_removed()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();

        $team->addUser($user);

        $team->load('users');
        $this->assertTrue($team->users->contains($user));

        $team->removeUser($user);

        $team->load('users');
        $this->assertFalse($team->users->contains($user));
    }

    /**
     *
     * @test
     */
    public function a_team_can_have_users()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();

        $team->users()->attach($user);

        $this->assertTrue($team->users->contains($user));
    }

    /**
     *
     * @test
     */
    public function a_team_can_have_blogsGroups()
    {
        $team = factory(Team::class)->create();
        $blogGroup = factory(BlogGroup::class)->make();

        $team->blogGroups()->save($blogGroup);

        $this->assertTrue($team->blogGroups->contains($blogGroup));
    }

    /**
     *
     * @test
     */
    public function a_team_can_have_blogs()
    {
        $team = factory(Team::class)->create();
        $blog = factory(Blog::class)->make();

        $team->blogs()->save($blog);

        $this->assertTrue($team->blogs->contains($blog));
    }

    /**
     *
     * @test
     */
    public function a_team_can_have_leadBoxes()
    {
        $team = factory(Team::class)->create();
        $leadBox = factory(Leadbox::class)->make();

        $team->blogs()->save($leadBox);

        $this->assertTrue($team->leadBoxes->contains($leadBox));
    }
}
