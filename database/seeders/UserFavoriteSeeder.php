<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserFavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userFavorites = [
            [
                'user_id' => 1,
                'quote_id' => 1,
            ],
            [
                'user_id' => 1,
                'quote_id' => 2,
            ],
        ];

        foreach ($userFavorites as $userFavorite) {
            DB::table('user_favorites')->insert($userFavorite);
        }
    }
}
