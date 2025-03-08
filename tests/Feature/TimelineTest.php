<?php

namespace Tests\Feature;

use App\Models\Timeline;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Throwable;

class TimelineTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     * @throws Throwable
    */
    public function a_guest_user_can_visit_timeline_page()
    {
        $this->get(route('timeline.index'))
            ->assertOk()
            ->assertInertia(fn(Assert $page) =>
                $page->component('Timeline/Index')
                     ->has('timelines')
            );
    }
    
    /**
     * @test
     * @throws \Throwable
    */
    public function root_user_can_post_timeline_posts()
    {
        // Auth as Root user
        $this->actingAs(User::factory()->create([
            'email' => config('app.users.root')
        ]));
        
        // get timeline post data
        $post = Timeline::factory()->make();
        
        // post timeline post
        $this->post(route('timeline.store'), $post->toArray())
            ->assertRedirect(route('timeline.index'));
        
        $this->assertDatabaseHas('timelines', [
            'author_id' => auth()->id(),
            'title' => $post->title,
            'slug' => $post->slug,
            'description' => $post->description,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
        ]);
    }
    
    /**
     * @test
     * @throws \Throwable
    */
    public function root_user_can_visit_create_timeline_post()
    {
        // Auth as Root user
        $this->actingAs(User::factory()->create([
            'email' => config('app.users.root')
        ]));
        
        $this->get(route('timeline.create'))
            ->assertOk()
            ->assertInertia(fn(Assert $page) =>
                $page->component('Timeline/Create')
            );
    }
}
