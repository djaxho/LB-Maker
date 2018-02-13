<?php

namespace Tests\Feature;

use App\Leadbox;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LeadboxTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function only_authenticated_users_can_view_their_teams_leadboxes()
    {
        $this->withExceptionHandling()->signIn();

        // Create leadbox that is not owned by user nor in the same team
        $leadbox = factory(Leadbox::class)->create();

        $this->get("/leadboxes/{$leadbox->id}")
            ->assertStatus(403);

        // Associate the leadbox with the authorized user's team
        $leadbox->team()->associate(auth()->user()->team())->save();

        // Make sure the user doesnt own the leadbox itself
        $this->assertFalse($leadbox->user_id == auth()->id());

        // Check if the leadbox is accessible to the authenticated user simply because
        // It is associated with the same team
        $this->get("/leadboxes/{$leadbox->id}")
            ->assertSee($leadbox->name);
    }

    /** @test */
    function authenticated_user_can_only_see_index_of_teams_leadboxes()
    {
        $this->signIn();

        $leadbox = factory(Leadbox::class, 15)->create([
            'team_id' => auth()->user()->team()->id,
        ]);

        $leadboxOutsideOfTeam = factory(Leadbox::class)->create();

        // check if all leadboxes in user's teams are visible
        // and the leadbox not in the team cannot be seen
        $this->get("/leadboxes")
            ->assertSee($leadbox[0]->name)
            ->assertSee($leadbox[5]->name)
            ->assertSee($leadbox[14]->name)
            ->assertDontSee($leadboxOutsideOfTeam->name);
    }

    /** @test */
    function an_authenticated_user_can_create_new_leadboxes()
    {
        $this->signIn();

        // make new model but don't persist
        $leadbox = factory(Leadbox::class)->make([
            'user_id' => auth()->id(),
            'team_id' => auth()->user()->team()->id,
        ]);

        $response = $this->post('/leadboxes', $leadbox->toArray());
        $this->get($response->headers->get('Location'))
            ->assertSee($leadbox->name);
    }

    /** @test */
    function a_leadbox_requires_a_blog()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $leadbox = factory(Leadbox::class)->make(['blog_id' => null]);

        $this->post('/leadboxes', $leadbox->toArray())
            ->assertSessionHasErrors('blog_id');;
    }

    /** @test */
    function a_leadbox_requires_a_name()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $leadbox = factory(Leadbox::class)->make(['name' => null]);

        $this->post('/leadboxes', $leadbox->toArray())
            ->assertSessionHasErrors('name');;
    }

    /** @test */
    function a_leadbox_requires_a_url()
    {
        $this->withExceptionHandling()->signIn();

        // make new model but don't persist
        $leadbox = factory(Leadbox::class)->make(['url' => null]);

        $this->post('/leadboxes', $leadbox->toArray())
            ->assertSessionHasErrors('url');;
    }

    /** @test */
    function unauthorized_users_may_not_delete_leadbox()
    {
        $this->withExceptionHandling();

        $leadbox = factory(Leadbox::class)->create();

        // Guests
        $this->delete('leadboxes/'.$leadbox->id)
            ->assertRedirect('/login');

        $this->signIn();

        // Join same team so resource is available to see
        $leadbox->team()->associate(auth()->user()->team()->id)->save();

        // Team-members of leadbox creator should not see delete button
        $this->get('leadboxes/'.$leadbox->id)
            ->assertDontSee('Delete');

        // Team-members of leadbox creator should access delete method/route
        $this->delete('leadboxes/'.$leadbox->id)
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_their_own_leadboxes()
    {
        $this->withExceptionHandling()->signIn();

        $leadbox = factory(Leadbox::class)->make();

        $leadbox->user()->associate(auth()->user())->save();

        $this->assertDatabaseHas('leadboxes', ['id' => $leadbox->id]);

        $this->get('leadboxes/'.$leadbox->id)
            ->assertSee('Delete');

        $this->delete('leadboxes/'.$leadbox->id);

        $this->assertDatabaseMissing('leadboxes', ['id' => $leadbox->id]);
    }

}
