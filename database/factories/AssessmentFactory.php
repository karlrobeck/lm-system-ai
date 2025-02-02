<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\User;
use App\Models\Files;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition()
    {
        return [
            'user_id'  => User::factory(),
            'file_id'  => Files::factory(),
            'rank'     => $this->faker->numberBetween(1, 5),
            'modality' => $this->faker->randomElement(['reading', 'writing', 'auditory', 'kinesthetic', 'visualization']),
            'message'  => $this->faker->sentence,
        ];
    }
}