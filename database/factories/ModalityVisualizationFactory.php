<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModalityVisualization>
 */
class ModalityVisualizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image_file_id' => $this->faker->randomDigitNot(0),
            'question' => $this->faker->sentence(),
            'choices' => $this->faker->json()->randomElement([]),
            'choices' => json_encode($this->faker->sentences(4)),
            'correct_answer' => $this->faker->sentence(),
            'context_file_id' => $this->faker->randomDigitNot(0),
        ];
    }
}
