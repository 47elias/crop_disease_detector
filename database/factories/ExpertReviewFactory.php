<?php

namespace Database\Factories;

use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\ExpertReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpertReview>
 */
class ExpertReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diagnosis_id' => Diagnosis::factory(),
            'reviewer_id' => User::factory(),
            'verified_disease_id' => Disease::factory(),
            'is_correct' => fake()->boolean(75),
            'notes' => fake()->optional()->sentence(),
            'reviewed_at' => now(),
        ];
    }
}
