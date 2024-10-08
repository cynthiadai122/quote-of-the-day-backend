<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_add_favorite(): void
    {
        $user = User::factory()->create();
        $quote = Quote::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/favorite/toggle', [
                'quote_id' => $quote->id,
            ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Favorite added',
        ]);

        $this->assertDatabaseHas(
            'favorites', [
                'user_id' => $user->id,
                'quote_id' => $quote->id,
            ]
        );
    }

    public function testRemoveFavorite()
    {
        $user = User::factory()->create();
        $quote = Quote::factory()->create();
        Favorite::Create([
            'user_id' => $user->id,
            'quote_id' => $quote->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/favorite/toggle', ['quote_id' => $quote->id]);
        $response->assertStatus(200)->assertJson(['message' => 'Favorite removed']);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'quote_id' => $quote->id,
        ]);
    }

    public function test_authenticated_user_can_get_favorites()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $quote = Quote::factory()->create();
        Favorite::create([
            'user_id' => $user->id,
            'quote_id' => $quote->id,
        ]);

        $response = $this->getJson('/api/favorites');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    public function test_quote_is_favorite()
    {
        $user = User::factory()->create();
        $quote = Quote::factory()->create();

        Favorite::create([
            'user_id' => $user->id,
            'quote_id' => $quote->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/quote/{$quote->id}/is-favorite");

        $response->assertStatus(200)
            ->assertJson(['is_favorite' => true]);
    }

    public function test_quote_is_not_favorite()
    {
        $user = User::factory()->create();
        $quote = Quote::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/quote/{$quote->id}/is-favorite");

        $response->assertStatus(200)
            ->assertJson(['is_favorite' => false]);
    }
}
