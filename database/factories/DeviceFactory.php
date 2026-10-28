<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_uuid' => fake()->unique()->uuid(),
            'model' => fake()->word(),
            'android_version' => fake()->randomElement(['11', '12', '13', '14']),
            'ram_mb' => fake()->numberBetween(2048, 8192),
            'app_version' => '1.0.0',
        ];
    }
}
