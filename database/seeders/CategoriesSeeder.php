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
        Category::create(['name' => 'Inspirational']);
        Category::create(['name' => 'Life']);
        Category::create(['name' => 'Love']);
    }
}
