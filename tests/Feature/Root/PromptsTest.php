<?php

namespace Tests\Feature\Root;

use App\Models\Prompts\Prompt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class PromptsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @throws \Throwable
    */
    public function unauthorized_user_cannot_access_root_prompt_pages()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('root.prompts.index'));
        $response->assertRedirect(route('dashboard'));

        $response = $this->get(route('root.prompts.create'));
        $response->assertRedirect(route('dashboard'));

        $response = $this->post(route('root.prompts.store', $this->make(Prompt::class)->toArray()));
        $response->assertRedirect(route('dashboard'));

        $prompt = $this->create(Prompt::class);
        $response = $this->post(route('root.prompts.store', $prompt->toArray()),
            $this->make(Prompt::class)->toArray()
        );
        $response->assertRedirect(route('dashboard'));

        $response = $this->get(route('root.prompts.edit', $this->create(Prompt::class)));
        $response->assertRedirect(route('dashboard'));

        $response = $this->delete(route('root.prompts.destroy', $this->create(Prompt::class)));
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * @test
     * @throws \Throwable
    */
    public function authorized_user_can_view_prompts_index()
    {
        $this->signInAsRoot();

        $prompts = $this->create(Prompt::class, [], 3);

        $response = $this->get(route('root.prompts.index'));

        $response->assertOk();
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Root/Prompts/Index')
            ->has('prompts')
        );
    }

    /**
     * @test
     * @throws \Throwable
    */
    public function authorized_user_can_store_a_prompt()
    {
        $this->signInAsRoot();

        $promptData = $this->make(Prompt::class, ['user_id' => null]);

        $response = $this->post(route('root.prompts.store'), $promptData->toArray());

        $response->assertRedirect();
        $this->assertDatabaseHas('prompts', [
            'author_id' => auth()->user()->id,
            'name' => $promptData->name,
            'description' => $promptData->description,
        ]);
    }

    /**
     * @test
     * @throws \Throwable
    */
    public function authorized_user_can_view_a_prompt()
    {
        $this->signInAsRoot();

        $prompt = $this->create(Prompt::class);

        $response = $this->get(route('root.prompts.show', $prompt));

        $response->assertOk();
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Root/Prompts/Show')
            ->has('prompt')
        );
    }

    /**
     * @test
     * @throws \Throwable
    */
    public function authorized_user_can_view_create_prompt_page()
    {
        $this->signInAsRoot();

        $response = $this->get(route('root.prompts.create'));

        $response->assertOk();
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Root/Prompts/Create')
        );
    }

    /**
     * @test
     * @throws \Throwable
    */
    public function authorized_user_can_edit_a_prompt()
    {
        $this->signInAsRoot();

        $prompt = $this->create(Prompt::class);

        $response = $this->get(route('root.prompts.edit', $prompt));

        $response->assertOk();
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Root/Prompts/Edit')
            ->has('prompt')
        );
    }

    /**
     * @test
     * @throws \Throwable
    */
    public function authorized_user_can_update_a_prompt()
    {
        $this->signInAsRoot();

        $prompt = $this->create(Prompt::class);
        $promptData = $this->make(Prompt::class, ['author_id' => null]);

        $response = $this->put(route('root.prompts.update', $prompt), $promptData->toArray());

        $response->assertRedirect();
        $this->assertDatabaseHas('prompts', [
            'id' => $prompt->id,
            'author_id' => $prompt->author->id,
            'name' => $promptData->name,
            'description' => $promptData->description,
        ]);
    }

    /**
     * @test
     * @throws \Throwable
    */
    public function authorized_user_can_delete_a_prompt()
    {
        $this->signInAsRoot();

        $prompt = $this->create(Prompt::class);

        $response = $this->delete(route('root.prompts.destroy', $prompt));

        $response->assertRedirect();
        $this->assertDatabaseMissing('prompts', [
            'id' => $prompt->id,
        ]);
    }
}