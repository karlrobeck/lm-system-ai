<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModalityAuditory>
 */
class ModalityAuditoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'audio_file_id' => $this->faker->randomDigitNot(0),
            'correct_answer' => $this->faker->sentence(),
            'context_file_id' => $this->faker->randomDigitNot(0),
        ];
    }
}
