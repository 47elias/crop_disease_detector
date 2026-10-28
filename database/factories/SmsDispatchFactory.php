<?php

namespace Database\Factories;

use App\Enums\DispatchStatus;
use App\Models\Device;
use App\Models\Diagnosis;
use App\Models\SmsDispatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SmsDispatch>
 */
class SmsDispatchFactory extends Factory
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
            'device_id' => Device::factory(),
            'recipient_phone' => '+26377'.fake()->numerify('#######'),
            'message_body' => fake()->text(100),
            'status' => DispatchStatus::Queued,
        ];
    }
}
