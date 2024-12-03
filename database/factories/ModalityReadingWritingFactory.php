<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModalityReadingWriting>
 */
class ModalityReadingWritingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mode' => $this->faker->randomElement(['reading', 'writing']),
            'question' => $this->faker->sentence(),
            'context_answer' => $this->faker->sentence(),
        ];
    }
}
