<?php

namespace Database\Factories;

use App\Models\FieldTrial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FieldTrial>
 */
class FieldTrialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'ward' => fake()->citySuffix().' Ward',
            'starts_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'ends_at' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'description' => fake()->sentence(),
        ];
    }
}
