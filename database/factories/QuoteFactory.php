<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quote' => $this->faker->sentence,
            'author' => $this->faker->name,
            'length' => $this->faker->numberBetween(50, 200),
            'language' => 'en',
            'tags' => json_encode([$this->faker->word, $this->faker->word]),
            'permalink' => $this->faker->url,
            'title' => $this->faker->word,
            'background' => $this->faker->imageUrl,
            'date' => $this->faker->date,
            'category_id' => \App\Models\Category::factory(),
            'api_id' => (string) Str::uuid(),
        ];
    }
}
