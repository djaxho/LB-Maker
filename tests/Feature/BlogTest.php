<?php

namespace Tests\Feature;

use App\Blog;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function only_authenticated_users_can_view_their_teams_blogs()
    {
        $this->withExceptionHandling()->signIn();

        // Create blog that is not owned by user nor in the same team
        $blog = factory(Blog::class)->create();

        $this->get("/blogs/{$blog->id}")
            ->assertStatus(403);

        // Associate the blog with the authorized user's team
        $blog->team()->associate(auth()->user()->team())->save();

        // Make sure the user doesnt own the blog itself
        $this->assertFalse($blog->user_id == auth()->id());

        // Check if the blog is accessible to the authenticated user simply because
        // It is associated with the same team
        $this->get("/blogs/{$blog->id}")
            ->assertSee($blog->name);
    }

    /** @test */
    function authenticated_user_can_only_see_index_of_teams_blogs()
    {
        $this->signIn();

        $blog = factory(Blog::class, 15)->create([
            'team_id' => auth()->user()->team()->id,
        ]);

        $blogOutsideOfTeam = factory(Blog::class)->create();

        // check if all blogs in user's teams are visible
        // and the blog not in the team cannot be seen
        $this->get("/blogs")
            ->assertSee($blog[0]->name)
            ->assertSee($blog[5]->name)
            ->assertSee($blog[14]->name)
            ->assertDontSee($blogOutsideOfTeam->name);
    }

    /** @test */
    function an_authenticated_user_can_create_new_blogs()
    {
        $this->signIn();

        // make new model but don't persist
        $blog = factory(Blog::class)->make([
            'user_id' => auth()->id(),
            'team_id' => auth()->user()->team()->id,
        ]);

        $response = $this->post('/blogs', $blog->toArray());
        $this->get($response->headers->get('Location'))
            ->assertSee($blog->name);
    }

    /** @test */
    function a_blog_requires_a_blog_group()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blog = factory(Blog::class)->make(['blog_group_id' => null]);

        $this->post('/blogs', $blog->toArray())
            ->assertSessionHasErrors('blog_group_id');;
    }

    /** @test */
    function a_blog_requires_a_name()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blog = factory(Blog::class)->make(['name' => null]);

        $this->post('/blogs', $blog->toArray())
            ->assertSessionHasErrors('name');;
    }

    /** @test */
    function a_blog_requires_a_url()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blog = factory(Blog::class)->make(['url' => null]);

        $this->post('/blogs', $blog->toArray())
            ->assertSessionHasErrors('url');;
    }

    /** @test */
    function a_blog_requires_a_main_text()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blog = factory(Blog::class)->make(['main_text' => null]);

        $this->post('/blogs', $blog->toArray())
            ->assertSessionHasErrors('main_text');;
    }

    /** @test */
    function a_blog_requires_a_button_text()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blog = factory(Blog::class)->make(['button_text' => null]);

        $this->post('/blogs', $blog->toArray())
            ->assertSessionHasErrors('button_text');;
    }

    /** @test */
    function a_blog_requires_a_mailchimp_list()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blog = factory(Blog::class)->make(['mailchimp_list' => null]);

        $this->post('/blogs', $blog->toArray())
            ->assertSessionHasErrors('mailchimp_list');;
    }

    /** @test */
    function a_blog_requires_a_mailchimp_group()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $blog = factory(Blog::class)->make(['mailchimp_group' => null]);

        $this->post('/blogs', $blog->toArray())
            ->assertSessionHasErrors('mailchimp_group');;
    }

    /** @test */
    function unauthorized_users_may_not_delete_blog()
    {
        $this->withExceptionHandling();

        $blog = factory(Blog::class)->create();

        // Guests
        $this->delete('blogs/'.$blog->id)
            ->assertRedirect('/login');

        $this->signIn();

        // Join same team so resource is available to see
        $blog->team()->associate(auth()->user()->team()->id)->save();

        // Team-members of blog creator should not see delete button
        $this->get('blogs/'.$blog->id)
            ->assertDontSee('Delete');

        // Team-members of blog creator should access delete method/route
        $this->delete('blogs/'.$blog->id)
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_their_own_blogs()
    {
        $this->withExceptionHandling()->signIn();

        $blog = factory(Blog::class)->make();

        $blog->user()->associate(auth()->user())->save();

        $this->assertDatabaseHas('blogs', ['id' => $blog->id]);

        $this->get('blogs/'.$blog->id)
            ->assertSee('Delete');

        $this->delete('blogs/'.$blog->id);

        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }

}

