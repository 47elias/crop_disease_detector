<?php

namespace Database\Factories;

use App\Enums\DiagnosisStatus;
use App\Models\Diagnosis;
use App\Models\DiagnosisImage;
use App\Models\Disease;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Diagnosis>
 */
class DiagnosisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diagnosis_image_id' => DiagnosisImage::factory(),
            'disease_id' => Disease::factory(),
            'confidence' => fake()->randomFloat(4, 0.5, 1),
            'top_k_predictions' => [],
            'status' => DiagnosisStatus::PendingReview,
            'local_uuid' => fake()->unique()->uuid(),
        ];
    }
}
