<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Can;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_get_all_categories(){
        Category::factory()->count(5)->create();
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function test_update_user_preference(){
        $user = User::factory()->create([
            'password'=> Hash::make('password')
        ]);
        $categories = Category::factory()->count(3)->create();
        $this->actingAs($user,'sanctum');
        $categoryIds = $categories->pluck('id')->toArray();
        $response = $this->postJson('/api/categories',[
            'categories'=>$categoryIds,
        ]);

        $response->assertStatus(200)->assertJson(['message'=> 'Preferences updated succussfully!']);
        foreach($categoryIds as $categoryId){
            $this->assertDatabaseHas('user_categories',[
                'user_id' => $user->id,
                'category_id'=> $categoryId,
            ]);
        }
    }

}
