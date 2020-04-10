<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikesTest extends TestCase
{
    use DatabaseMigrations;

    protected $post;

    public function setUp(): void
    {
        parent::setUp();
        $this->post = factory(Post::class)->create();
        $this->signIn();
    }

    public function test_it_a_can_like_a_post()
    {
        $this->post->like();

        $this->assertDatabaseHas('likes',[
            'user_id' => $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post)
        ]);

        $this->assertTrue($this->post->isLiked());
    }

    public function test_it_a_user_can_unlike_a_post()
    {
        $this->post->like();
        $this->post->unlike();

        $this->assertDatabaseMissing('likes',[
            'user_id' => $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post)
        ]);

        $this->assertFalse($this->post->isLiked());
    }

    public function test_it_a_user_may_toggle_a_posts_like_status()
    {
        $this->post->toggle();

        $this->assertTrue($this->post->isLiked());

        $this->post->toggle();

        $this->assertFalse($this->post->isLiked());
    }

    public function test_it_a_post_knows_many_likes_it_has()
    {
        $this->post->toggle();

        $this->assertEquals(1 , $this->post->likesCount);
    }
}
