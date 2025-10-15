<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->company(),
            'description' => fake()->text(),
            'location' => fake()->city(),
            'date' => fake()->date(),
            'category_id' => fake()->numberBetween(1, 10),
            'available_seats' => fake()->numberBetween(1, 100),
            'is_active' => fake()->randomElement([true, false])
        ];
    }
}
