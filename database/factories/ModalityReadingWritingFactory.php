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

        $mode = $this->faker->randomElement(['reading', 'writing']);

        return [
            'mode' => $mode,
            'question' => $this->faker->sentence(),
            'context_answer' => $this->faker->sentence(),
            'choices' => $mode === 'reading' ? json_encode($this->faker->words(4)) : null,
            'test_type' => $this->faker->randomElement(['pre', 'post']),
        ];
    }
}
