<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModalityKinesthetic>
 */
class ModalityKinestheticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_id' => $this->faker->numberBetween(1, 100),
            'question' => $this->faker->sentence(),
            'context_answer' => $this->faker->paragraph(),
            'question_index' => $this->faker->unique()->randomNumber(),
            'test_type' => $this->faker->randomElement(['pre', 'post']),
        ];
    }
}
