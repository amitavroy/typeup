<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClickEvent>
 */
class ClickEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'search_id' => \App\Models\Search::factory(),
            'content_id' => fake()->slug(2),
            'position' => fake()->numberBetween(1, 20),
            'metadata' => [
                'element_type' => fake()->randomElement(['card', 'button', 'link']),
                'click_time' => fake()->dateTimeBetween('-1 hour', 'now')->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
