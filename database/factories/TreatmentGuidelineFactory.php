<?php

namespace Database\Factories;

use App\Models\Disease;
use App\Models\TreatmentGuideline;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentGuideline>
 */
class TreatmentGuidelineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'disease_id' => Disease::factory(),
            'language' => 'en',
            'title' => fake()->sentence(4),
            'steps' => [fake()->sentence(), fake()->sentence()],
            'chemical_options' => fake()->sentence(),
            'cultural_practices' => fake()->sentence(),
            'sms_text' => fake()->text(120),
        ];
    }
}
