<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function root_user_can_store_tags()
    {
        $this->actingAs($this->rootUser());

        $response = $this->post(route('root.tags.store'), [
            'name' => 'agentes',
        ]);

        //        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseHas('tags', [
            'name' => 'agentes',
        ]);
    }
}
