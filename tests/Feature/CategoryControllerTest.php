<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_categories()
    {
        Category::factory()->count(5)->create();
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }
}
