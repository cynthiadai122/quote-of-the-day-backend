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
        Category::create(['name' => 'inspire', 'image' => 'https://theysaidso.com/assets/images/qod/qod-inspire.jpg']);
        Category::create(['name' => 'life', 'image' => 'https://theysaidso.com/assets/images/qod/qod-life.jpg']);
        Category::create(['name' => 'love', 'image' => 'https://theysaidso.com/assets/images/qod/qod-love.jpg']);
        Category::create(['name' => 'management', 'image' => 'https://theysaidso.com/assets/images/qod/qod-management.jpg']);
        Category::create(['name' => 'sports', 'image' => 'https://theysaidso.com/assets/images/qod/qod-sports.jpg']);
        Category::create(['name' => 'funny', 'image' => 'https://theysaidso.com/assets/images/qod/qod-funny.jpg']);
        Category::create(['name' => 'art', 'image' => 'https://theysaidso.com/assets/images/qod/qod-art.jpg']);
        Category::create(['name' => 'students', 'image' => 'https://theysaidso.com/assets/images/qod/qod-students.jpg']);
    }
}
