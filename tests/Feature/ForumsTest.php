<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForumsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_view_forum_index(): void
    {
        $this->markTestSkipped();
        $response = $this->get(route('forums.index'));
        $response->assertStatus(200);
        $response->assertViewIs('forums.index');
    }

    public function test_user_can_view_forum_category(): void
    {
        $this->markTestSkipped();
        $category = Category::factory()->create();

        $response = $this->get(route('forums.categories.show', $category));

        $response->assertStatus(200);
        $response->assertViewIs('forums.categories.show');
        $response->assertSee($category->name);
    }

    public function test_auth_user_can_create_post(): void
    {
        $this->markTestSkipped();
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $postData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category_id' => $category->id,
        ];

        $response = $this->actingAs($user)
                        ->post(route('forums.posts.store'), $postData);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_posts', [
            'title' => $postData['title'],
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_create_post(): void
    {
        $this->markTestSkipped();
        $category = Category::factory()->create();

        $postData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'category_id' => $category->id,
        ];

        $response = $this->post(route('forums.posts.store'), $postData);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('forum_posts', [
            'title' => $postData['title'],
        ]);
    }

    public function test_user_can_view_post(): void
    {
        $this->markTestSkipped();
        $post = Post::factory()->create();

        $response = $this->get(route('forums.posts.show', $post));

        $response->assertStatus(200);
        $response->assertViewIs('forums.posts.show');
        $response->assertSee($post->title);
    }

    public function test_auth_user_can_reply_to_post(): void
    {
        $this->markTestSkipped();
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $replyData = [
            'content' => $this->faker->paragraph,
        ];

        $response = $this->actingAs($user)
                        ->post(route('forums.replies.store', $post), $replyData);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_replies', [
            'content' => $replyData['content'],
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_guest_cannot_reply_to_post(): void
    {
        $this->markTestSkipped();
        $post = Post::factory()->create();

        $replyData = [
            'content' => $this->faker->paragraph,
        ];

        $response = $this->post(route('forums.replies.store', $post), $replyData);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('forum_replies', [
            'content' => $replyData['content'],
            'post_id' => $post->id,
        ]);
    }

    public function test_owner_can_update_post(): void
    {
        $this->markTestSkipped();
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated content for this post.'
        ];

        $response = $this->actingAs($user)
                        ->put(route('forums.posts.update', $post), $updatedData);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated content for this post.'
        ]);
    }

    public function test_non_owner_cannot_update_post(): void
    {
        $this->markTestSkipped();
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated by non-owner',
            'content' => 'This update should fail.'
        ];

        $response = $this->actingAs($anotherUser)
                        ->put(route('forums.posts.update', $post), $updatedData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('forum_posts', [
            'id' => $post->id,
            'title' => 'Updated by non-owner',
        ]);
    }

    public function test_owner_can_update_reply(): void
    {
        $this->markTestSkipped();
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $reply = Reply::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        $updatedData = [
            'content' => 'Updated reply content.'
        ];

        $response = $this->actingAs($user)
                        ->put(route('forums.replies.update', $reply), $updatedData);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_replies', [
            'id' => $reply->id,
            'content' => 'Updated reply content.'
        ]);
    }

    public function test_owner_can_delete_post(): void
    {
        $this->markTestSkipped();
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
                        ->delete(route('forums.posts.destroy', $post));

        $response->assertRedirect();
        $this->assertSoftDeleted('forum_posts', ['id' => $post->id]);
    }

    public function test_owner_can_delete_reply(): void
    {
        $this->markTestSkipped();
        $user = User::factory()->create();
        $reply = Reply::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
                        ->delete(route('forums.replies.destroy', $reply));

        $response->assertRedirect();
        $this->assertSoftDeleted('forum_replies', ['id' => $reply->id]);
    }
}
