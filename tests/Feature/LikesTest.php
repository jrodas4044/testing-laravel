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

    public function test_it_a_can_like_a_post()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $post->like();

        $this->assertDatabaseHas('likes',[
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertTrue($post->isLiked());
    }

    public function test_it_a_user_can_unlike_a_post()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $post->like();
        $post->unlike();

        $this->assertDatabaseMissing('likes',[
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertFalse($post->isLiked());

    }

    public function test_it_a_user_may_toggle_a_posts_like_status()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $post->toggle();

        $this->assertTrue($post->isLiked());

        $post->toggle();

        $this->assertFalse($post->isLiked());
    }

    public function test_it_a_post_knows_many_likes_it_has()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $post->toggle();

        $this->assertEquals(1 , $post->likesCount);
    }
}
