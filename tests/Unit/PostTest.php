<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    protected $post;

    public function setUp()
    {
        parent::setUp();

        $this->post = create('App\Post');
    }

    /** @test */
    function a_post_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->post->creator);
    }
}
