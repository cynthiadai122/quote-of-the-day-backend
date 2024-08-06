<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure that IDs are auto-incremented if not explicitly setting them
        Category::create(['name' => 'inspire']);
        Category::create(['name' => 'life']);
        Category::create(['name' => 'love']);
        Category::create(['name' => 'management']);
        Category::create(['name' => 'sports']);
        Category::create(['name' => 'funny']);
        Category::create(['name' => 'art']);
        Category::create(['name' => 'students']);
    }
}
