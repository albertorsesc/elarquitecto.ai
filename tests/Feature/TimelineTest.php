<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\Timeline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TimelineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function root_user_can_post_timeline_posts()
    {
        // Auth as Root user
        $this->actingAs($this->rootUser());

        $tags = Tag::factory()->count(2)->create();
        // get timeline post data
        $post = Timeline::factory()->make([
            'title' => 'My First Post',
        ]);

        // post timeline post
        $this->post(route('root.timeline.store'), [
            ...$post->toArray(),
            'tags' => [...$tags->pluck('id')->toArray()],
        ])->assertRedirect(route('timeline.index'));

        $this->assertDatabaseHas('timelines', [
            'author_id' => auth()->id(),
            'title' => $post->title,
            'slug' => Str::slug($post->title),
            'description' => $post->description,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
        ]);

        $this->assertDatabaseHas('timeline_tags', [
            'timeline_id' => Timeline::first()->id,
            'tag_id' => $tags->first()->id,
        ]);

        $this->assertDatabaseHas('timeline_tags', [
            'timeline_id' => Timeline::first()->id,
            'tag_id' => $tags->last()->id,
        ]);
    }

    /**
     * @test
     */
    public function root_user_can_visit_create_timeline_post()
    {
        $this->actingAs($this->rootUser());

        $this->get(route('root.timeline.create'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page->component('Timeline/Create')
            );
    }
}
