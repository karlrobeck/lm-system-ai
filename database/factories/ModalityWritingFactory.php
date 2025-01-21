<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModalityWriting>
 */
class ModalityWritingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_id' => $this->faker->randomDigitNotNull(),
            'question' => $this->faker->sentence(),
            'context_answer' => $this->faker->paragraph(),
            'question_index' => $this->faker->unique()->word(),
            'test_type' => $this->faker->randomElement(['pre', 'post']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
