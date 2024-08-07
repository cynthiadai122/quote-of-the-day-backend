<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(QuotesSeeder::class);
        $this->call(UserCategorySeeder::class);
        $this->call(UserQuoteSeeder::class);
        $this->call(UserFavoriteSeeder::class);
    }
}
