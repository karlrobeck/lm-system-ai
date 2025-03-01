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
            'file_id' => $this->faker->randomDigitNotNull(),
            'question' => $this->faker->sentence(),
            'choices' => json_encode($this->faker->words(4)),
            'question_index' => $this->faker->numberBetween(1, 100),
            'correct_answer' => $this->faker->word(),
            'test_type' => $this->faker->randomElement(['pre', 'post']),
        ];
    }
}
