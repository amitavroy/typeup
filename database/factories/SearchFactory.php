<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Search>
 */
class SearchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_id' => \App\Models\Site::factory(),
            'search_id' => fake()->uuid(),
            'metadata' => [
                'query' => fake()->words(3, true),
                'user_agent' => fake()->userAgent(),
                'referrer' => fake()->url(),
            ],
        ];
    }
}
