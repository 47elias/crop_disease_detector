<?php

namespace Database\Factories;

use App\Models\MlModelVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MlModelVersion>
 */
class MlModelVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'version' => fake()->unique()->semver(),
            'file_path' => 'models/model.tflite',
            'size_mb' => fake()->randomFloat(2, 1, 14.99),
            'quantisation' => 'int8',
            'accuracy' => fake()->randomFloat(4, 0.5, 1),
            'precision' => fake()->randomFloat(4, 0.5, 1),
            'recall' => fake()->randomFloat(4, 0.5, 1),
            'f1_score' => fake()->randomFloat(4, 0.5, 1),
            'is_active' => false,
            'released_at' => now(),
        ];
    }
}
