<?php

namespace Tests\Feature\Root;

use App\Models\Category;
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

    public function test_root_user_can_view_create_category_page()
    {
        $response = $this->get(route('root.categories.create'));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Root/Categories/Create')
        );
    }

    public function test_root_user_can_create_category()
    {
        $newCategory = $this->make(Category::class);
        $response = $this->post(route('root.categories.store'), $newCategory->toArray());
        $response->assertRedirect(route('root.categories.index'));
        $this->assertDatabaseHas('categories', $newCategory->toArray());
    }

    public function test_root_user_can_view_category_show_page()
    {
        $category = $this->create(Category::class);
        $response = $this->get(route('root.categories.show', $category->slug));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Root/Categories/Show')
                ->has('category')
        );
    }
    
    
}
