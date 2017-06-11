<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Input;
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
