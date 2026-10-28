<?php

namespace Database\Factories;

use App\Models\FieldFeedback;
use App\Models\FieldTrial;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FieldFeedback>
 */
class FieldFeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field_trial_id' => FieldTrial::factory(),
            'user_id' => User::factory(),
            'usability_rating' => fake()->numberBetween(1, 5),
            'readability_score' => fake()->numberBetween(1, 5),
            'comments' => fake()->optional()->sentence(),
        ];
    }
}
