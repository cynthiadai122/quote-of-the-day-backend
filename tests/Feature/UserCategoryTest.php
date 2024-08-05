<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchUserCategory()
    {
        $user = User::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $user->favoriteCategories()->attach($categories->pluck('id'));

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user/categories');

        $response->assertStatus(200)
            ->assertJson($categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            })->toArray());

        foreach ($categories as $category) {
            $this->assertDatabaseHas('categories', [
                'id' => $category->id,
                'name' => $category->name,
            ]);
        }
    }

    public function test_update_user_preference()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $categories = Category::factory()->count(3)->create();
        $this->actingAs($user, 'sanctum');
        $categoryIds = $categories->pluck('id')->toArray();
        $response = $this->postJson('/api/user/categories', [
            'categories' => $categoryIds,
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Preferences updated succussfully!']);
        foreach ($categoryIds as $categoryId) {
            $this->assertDatabaseHas('user_categories', [
                'user_id' => $user->id,
                'category_id' => $categoryId,
            ]);
        }
    }
}
