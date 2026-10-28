<?php

namespace Database\Factories;

use App\Models\DiagnosisImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DiagnosisImage>
 */
class DiagnosisImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'local_uuid' => fake()->unique()->uuid(),
            'file_path' => 'diagnosis-images/example.jpg',
            'thumbnail_path' => 'diagnosis-images/thumbnails/example.jpg',
            'original_filename' => 'leaf.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => 1024,
            'width' => 224,
            'height' => 224,
            'sha256' => fake()->sha256(),
            'captured_at' => now(),
            'capture_source' => 'camera',
        ];
    }
}
