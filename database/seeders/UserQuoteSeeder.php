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
            ],
            [
                'user_id' => 1,
                'quote_id' => 2,
            ],
        ];

        foreach ($userQuotes as $userQuote) {
            DB::table('user_quote')->insert($userQuote);
        }
    }
}
