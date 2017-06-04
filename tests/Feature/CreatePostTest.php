<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreatePostTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->get('/posts')
            ->assertRedirect('/login');

        $this->get('/posts/create')
            ->assertRedirect('/login');

        $this->post('/posts')
            ->assertRedirect('/login');
    }


    /** @test */
    function an_authenticated_user_can_create_new_post()
    {
        $this->signIn();

        $post = make('App\Post');

        $response = $this->post('/posts', $post->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($post->title)
            ->assertSee($post->body);
    }

    /** @test */
    function a_post_requires_a_title()
    {
        $this->publishPost(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_post_requires_a_body()
    {
        $this->publishPost(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_users_may_not_delete_posts()
    {
        $post = create('App\Post');

        $this->delete("posts/$post->id")->assertRedirect('/login');
    }

    /** @test */
    function authorized_users_can_delete_own_posts()
    {
        $this->signIn();

        $post = create('App\Post', ['user_id' => auth()->id()]);

        $this->json('DELETE', "posts/$post->id");

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /** @test */
    function authorized_users_cannot_delete_other_posts()
    {
        $this->signIn();

        $otherUser = create(User::class);

        $post = create('App\Post', ['user_id' => $otherUser->id]);

        $this->json('DELETE', "posts/$post->id")
            ->assertStatus(403);
    }

    /**
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function publishPost($overrides = [])
    {
        $this->signIn();

        $post = make('App\Post', $overrides);

        return $this->post('/posts', $post->toArray());
    }
}
