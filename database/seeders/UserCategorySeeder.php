<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCategories = [
            [
                'user_id' => 1,
                'category_id' => 1,
            ],
            [
                'user_id' => 1,
                'category_id' => 2,
            ],
        ];

        foreach ($userCategories as $userCategory) {
            DB::table('user_categories')->insert($userCategory);
        }
    }
}
