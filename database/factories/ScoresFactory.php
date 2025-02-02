<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\UserFactory as User;
use Database\Factories\FilesFactory as File;
use Database\Factories\ScoresContextFactory as ScoresContext;

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
        return [
            'correct' => $this->faker->numberBetween(0, 100),
            'total' => $this->faker->numberBetween(0, 100),
            'test_type' => $this->faker->randomElement(['pre', 'post']),
            'modality' => $this->faker->randomElement(['auditory', 'reading', 'visualization', 'writing']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
