<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;

class QuotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Quote::create([
            'quote' => 'The only limit to our realization of tomorrow is our doubts of today.',
            'author' => 'Franklin D. Roosevelt',
            'category_id' => 1,
        ]);
        Quote::create([
            'quote' => 'Life is what happens when you’re busy making other plans.',
            'author' => 'John Lennon',
            'category_id' => 2,
        ]);
        Quote::create([
            'quote' => 'Love the life you live. Live the life you love.',
            'author' => 'Bob Marley',
            'category_id' => 3,
        ]);

    }
}
