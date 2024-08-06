<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quotes = [
            [
                'quote' => 'Do not worry if you have built your castles in the air. They are where they should be. Now put the foundations under them.',
                'author' => 'Henry David Thoreau',
                'length' => 122,
                'language' => 'en',
                'tags' => json_encode(['worry', 'dreams', 'inspire', 'air']),
                'permalink' => 'https://theysaidso.com/quote/henry-david-thoreau-do-not-worry-if-you-have-built-your-castles-in-the-air-they',
                'title' => 'Inspiring Quote of the Day',
                'background' => 'https://theysaidso.com/assets/images/qod/qod-inspire.jpg',
                'date' => '2024-08-04',
                'category_id' => 1,
                'api_id' => (string) Str::uuid(),
            ],
        ];
        foreach ($quotes as $quote) {
            DB::table('quotes')->insert($quote);
        }

    }
}
