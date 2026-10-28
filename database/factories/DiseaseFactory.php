<?php

namespace Database\Factories;

use App\Models\Crop;
use App\Models\Disease;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Disease>
 */
class DiseaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'crop_id' => Crop::factory(),
            'name' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'symptoms' => fake()->sentence(),
            'is_healthy_class' => false,
            'model_class_index' => fake()->unique()->numberBetween(0, 250),
            'severity_level' => 'moderate',
        ];
    }
}
