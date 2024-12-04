<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scores>
 */
class ScoresFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = $this->faker->numberBetween(1, 9);
        $score = $this->faker->numberBetween(0, $total);
        return [
            'user_id' => $this->faker->randomDigitNot(0),
            'modality' => $this->faker->randomElement(['auditory', 'reading-writing', 'visualization']),
            'score' => $score,
            'total' => $total,
            'correct' => $score,
            'incorrect' => $total - $score,
        ];
    }
}
