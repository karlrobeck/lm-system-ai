<?php

namespace Database\Factories;

use App\Models\ModalityVisualization;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModalityVisualizationFactory extends Factory
{
    protected $model = ModalityVisualization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'file_id'        => \App\Models\Files::factory(),
            'question_index' => $this->faker->unique()->numberBetween(1, 100),
            'test_type'      => $this->faker->randomElement(['pre', 'post']),
            'question'       => $this->faker->sentence(10),
            'choices'        => json_encode([
                $this->faker->word,
                $this->faker->word,
                $this->faker->word,
                $this->faker->word,
            ]),
            'correct_answer' => $this->faker->word,
            'image_prompt'   => $this->faker->sentence(6),
            'image_url'      => null, // Nullable field
        ];
    }
}