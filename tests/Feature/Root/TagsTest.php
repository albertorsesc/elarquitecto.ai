<?php

namespace Tests\Feature\Root;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class TagsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signInAsRoot();
    }

    public function test_root_user_can_view_tags_index_page()
    {
        $response = $this->get(route('root.tags.index'));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Root/Tags/Index')
            ->has('tags')
        );
    }

    public function test_root_user_can_view_tags_create_page()
    {
        $response = $this->get(route('root.tags.create'));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Root/Tags/Create')
            ->has('categories')
        );
    }

    public function test_root_user_can_create_tag()
    {
        $newTag = $this->make(Tag::class);

        $response = $this->post(route('root.tags.store'), $newTag->toArray());
        $response->assertRedirect(route('root.tags.index'));
        $response->assertSessionHas('success', 'Tag created successfully');
        $this->assertDatabaseHas('tags', $newTag->toArray());
    }

    public function test_root_user_can_view_tags_edit_page()
    {
        $tag = $this->create(Tag::class);
        $response = $this->get(route('root.tags.edit', $tag->slug));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Root/Tags/Edit')
            ->has('tag')
            ->has('categories')
        );
    }

    public function test_root_user_can_update_tag()
    {
        $tag = $this->create(Tag::class);
        $updatedTag = $this->make(Tag::class);
        $response = $this->put(route('root.tags.update', $tag->slug), $updatedTag->toArray());
        $response->assertRedirect(route('root.tags.index'));
        $response->assertSessionHas('success', 'Tag updated successfully');
        $this->assertDatabaseHas('tags', $updatedTag->toArray());
    }
}
