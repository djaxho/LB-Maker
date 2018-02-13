<?php

namespace Tests\Feature;

use App\Blog;
use App\BlogGroup;
use App\Leadbox;
use App\Team;
use App\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_user_can_join_team()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();

        $user = User::with('teams')->find($user->id);

        $this->assertEmpty($user->teams);

        $user->joinTeam($team);

        $user = User::with('teams')->find($user->id);

        $this->assertTrue($user->teams->contains($team));

    }

    /**
     *
     * @test
     */
    public function a_user_can_leave_team()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();

        $user->joinTeam($team);

        $user = User::with('teams')->find($user->id);
        $this->assertNotEmpty($user->teams);

        $user->leaveTeam($team);

        $user = User::with('teams')->find($user->id);
        $this->assertEmpty($user->teams);

    }

    /**
     *
     * @test
     */
    public function user_can_have_blogsGroups()
    {
        $user = factory(User::class)->create();
        $blogGroup = factory(BlogGroup::class)->make();

        $user->blogGroups()->save($blogGroup);

        $this->assertTrue($user->blogGroups->contains($blogGroup));

    }

    /**
     *
     * @test
     */
    public function user_can_have_blogs()
    {
        $user = factory(User::class)->create();
        $blog = factory(Blog::class)->make();

        $user->blogs()->save($blog);

        $this->assertTrue($user->blogs->contains($blog));

    }

    /**
     *
     * @test
     */
    public function user_can_have_leadBoxes()
    {
        $user = factory(User::class)->create();
        $leadBox = factory(Leadbox::class)->make();

        $user->blogs()->save($leadBox);

        $this->assertTrue($user->leadBoxes->contains($leadBox));

    }




}