<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeletePostTest extends TestCase
{
    use DatabaseMigrations;

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
}
