<?php

namespace Tests\Feature\Root;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $response->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Root/Tags/Index')
                ->has('tags')
        );
    }

    public function test_root_user_can_view_tags_create_page()
    {

    }

    public function test_root_user_can_create_tag()
    {

    }

    public function test_root_user_can_view_tags_edit_page()
    {

    }

    public function test_root_user_can_update_tag()
    {

    }
}
