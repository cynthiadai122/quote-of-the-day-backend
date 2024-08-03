<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_get_all_categories(){
        Category::factory()->count(5)->create();

        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $response = $this->actingAs($user, 'sanctum')->get('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }
}
