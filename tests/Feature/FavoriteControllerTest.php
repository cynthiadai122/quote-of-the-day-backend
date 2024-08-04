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
}
