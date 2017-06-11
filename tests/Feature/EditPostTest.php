<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditPostTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function authorized_users_can_edit_own_post()
    {
        $post = $this->editPost();

        $this->get("posts/$post->id/edit")
            ->assertSee($post->title)
            ->assertSee($post->body);

        $response = $this->makeNewPost($post->id, [
            'title' => 'new title',
            'body' => 'new body'
        ]);

        $this->get($response->headers->get('Location'))
            ->assertSee('new title')
            ->assertSee('new body');
    }

    /** @test */
    function authorized_users_canot_edit_other_post_get()
    {
        $otherUser = create(User::class);

        $this->get("posts/{$this->editPost($otherUser->id)->id}/edit")
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_canot_edit_other_post_post()
    {
        $otherUser = create(User::class);

        $this->post("posts/{$this->editPost($otherUser->id)->id}/update", [
            'title' => 'new title',
            'body' => 'new body'
        ])->assertStatus(403);
    }

    /** @test */
    function a_post_requires_a_title()
    {
        $post = $this->editPost();

        $this->makeNewPost($post->id, [
            'title' => '',
            'body' => 'new body'
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    function a_post_requires_a_body()
    {
        $post = $this->editPost();

        $this->makeNewPost($post->id, [
            'title' => 'new title',
            'body' => ''
        ])->assertSessionHasErrors('body');
    }

    /**
     * @param null $id
     * @return mixed
     */
    protected function editPost($id = null)
    {
        $this->signIn();
        return create('App\Post', ['user_id' => $id ?: auth()->id()]);
    }


    /**
     * @param $id
     * @param array $array
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeNewPost($id, array $array)
    {
        return $this->post("posts/$id/update", $array);
    }
}
