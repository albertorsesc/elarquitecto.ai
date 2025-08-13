<?php

namespace Tests\Feature\Root;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ResourcesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signInAsRoot();
    }

    public function test_root_can_view_resources_index()
    {
        $response = $this->get(route('root.resources.index'));
        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Root/Resources/Index'));
    }

    public function test_root_can_view_resource_creation_form()
    {
        $response = $this->get(route('root.resources.create'));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page->component('Root/Resources/Create'));
    }

    public function test_root_can_create_new_resource()
    {
        $this->markTestSkipped('Resource creation not implemented yet');
    }

    public function test_root_can_view_resource_details()
    {
        $this->markTestSkipped('Resource details not implemented yet');
    }

    public function test_root_can_view_resource_edit_form()
    {
        $this->markTestSkipped('Resource edit form not implemented yet');
    }

    public function test_root_can_update_resource()
    {
        $this->markTestSkipped('Resource update not implemented yet');
    }

    public function test_root_can_delete_resource()
    {
        $this->markTestSkipped('Resource deletion not implemented yet');
    }
}
