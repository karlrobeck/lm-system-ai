<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rank' => 1,
            'modality' => $this->faker->randomElement(['reading', 'writing', 'auditory', 'kinesthetic','visualization']),
            'message' => $this->faker->sentence(),
        ];
    }
}
