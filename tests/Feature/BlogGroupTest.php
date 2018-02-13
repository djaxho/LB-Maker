<?php

namespace Tests\Feature;

use App\BlogGroup;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogGroupTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function only_authenticated_users_can_view_their_teams_blogGroups()
    {
        $this->withExceptionHandling()->signIn();

        // Create blog group that is not owned by user nor in the same team
        $blogGroup = factory(BlogGroup::class)->create();

        $this->get("/blog-groups/{$blogGroup->id}")
            ->assertStatus(403);

        // Associate the blog group with the authorized user's team
        $blogGroup->team()->associate(auth()->user()->team())->save();

        // Make sure the user doesnt own the blog group itself
        $this->assertFalse($blogGroup->user_id == auth()->id());

        // Check if the blog group is accessible to the authenticated user simply because
        // It is associated with the same team
        $this->get("/blog-groups/{$blogGroup->id}")
            ->assertSee($blogGroup->name);
    }

    /** @test */
    function authenticated_user_can_only_see_index_of_teams_blogGroups()
    {
        $this->signIn();

        $blogGroup = factory(BlogGroup::class, 15)->create([
            'team_id' => auth()->user()->team()->id,
        ]);

        $blogGroupOutsideOfTeam = factory(BlogGroup::class)->create();

        // check if all blog groups in user's teams are visible
        // and the blog group not in the team cannot be seen
        $this->get("/blog-groups")
            ->assertSee($blogGroup[0]->name)
            ->assertSee($blogGroup[5]->name)
            ->assertSee($blogGroup[14]->name)
            ->assertDontSee($blogGroupOutsideOfTeam->name);
    }

    /** @test */
    function an_authenticated_user_can_create_new_blogGroups()
    {
        $this->signIn();

        // make new model but don't persist
        $blogGroup = factory(BlogGroup::class)->make([
            'user_id' => auth()->id(),
            'team_id' => auth()->user()->team()->id,
        ]);

        $response = $this->post('/blog-groups', $blogGroup->toArray());
        $this->get($response->headers->get('Location'))
            ->assertSee($blogGroup->name);
    }

    /** @test */
    function a_blogGroup_requires_a_name()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blogGroup = factory(BlogGroup::class)->make(['name' => null]);

        $this->post('/blog-groups', $blogGroup->toArray())
            ->assertSessionHasErrors('name');;
    }

    /** @test */
    function a_blogGroup_requires_a_mailchimp_key()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blogGroup = factory(BlogGroup::class)->make(['mailchimp_key' => null]);

        $this->post('/blog-groups', $blogGroup->toArray())
            ->assertSessionHasErrors('mailchimp_key');;
    }

    /** @test */
    function unauthorized_users_may_not_delete_blogGroup()
    {
        $this->withExceptionHandling();

        $blogGroup = factory(BlogGroup::class)->create();

        // Guests
        $this->delete('blog-groups/'.$blogGroup->id)
            ->assertRedirect('/login');

        $this->signIn();

        // Join same team so resource is available to see
        $blogGroup->team()->associate(auth()->user()->team()->id)->save();

        // Team-members of blog-group creator should not see delete button
        $this->get('blog-groups/'.$blogGroup->id)
            ->assertDontSee('Delete');

        // Team-members of blog-group creator should access delete method/route
        $this->delete('blog-groups/'.$blogGroup->id)
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_their_own_blog_groups()
    {
        $this->withExceptionHandling()->signIn();

        $blogGroup = factory(BlogGroup::class)->make();

        $blogGroup->user()->associate(auth()->user())->save();

        $this->assertDatabaseHas('blog_groups', ['id' => $blogGroup->id]);

        $this->get('blog-groups/'.$blogGroup->id)
            ->assertSee('Delete');

        $this->delete('blog-groups/'.$blogGroup->id);

        $this->assertDatabaseMissing('blog_groups', ['id' => $blogGroup->id]);
    }

}

