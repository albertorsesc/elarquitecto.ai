<?php

namespace Tests\Feature\Root;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;
class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signInAsRoot();
    }

    public function test_root_user_can_view_categories_index_page()
    {
        $response = $this->get(route('root.categories.index'));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Root/Categories/Index')
                ->has('categories')
        );
    }
}
