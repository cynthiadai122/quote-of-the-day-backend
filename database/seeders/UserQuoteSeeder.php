<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userQuotes = [
            [
                'user_id' => 1,
                'quote_id' => 1,
                'created_at' => now(),
            ],
            [
                'user_id' => 1,
                'quote_id' => 2,
                'created_at' => now()->subDays(1),
            ],
            [
                'user_id' => 1,
                'quote_id' => 3,
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($userQuotes as $userQuote) {
            DB::table('user_quotes')->insert($userQuote);
        }
    }
}
