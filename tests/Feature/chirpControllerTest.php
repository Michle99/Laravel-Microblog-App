<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Chirp;
use Illuminate\Http\Response;

class chirpControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_chirp_index_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/chirps');

        $response->assertOk();
    }

    public function test_chirp_message_can_be_stored(): void
    {
        $payload = Chirp::factory()->make()->getAttributes();
        $user = User::factory()->create();

        $response = $this
        ->actingAs($user)
        ->post('/chirps', $payload);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/chirps');

        $user->refresh();

        $this->assertDatabaseHas('chirps', $payload);
    }

    public function test_chirp_can_be_updated(): void
    {
        $payload = Chirp::factory()->make()->getAttributes();
        $user = User::factory()->create();

        $response = $this
        ->actingAs($user)
        ->patch('/chirps/$payload', $payload);

        $response
            ->assertSessionHasNoErrors();

        $user->refresh();
    }
}
