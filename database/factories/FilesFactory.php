<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Files>
 */
class FilesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->url(),
            'name' => $this->faker->sentence(),
            'ready' => $this->faker->boolean(),
            'type' => $this->faker->randomElement(['pdf', 'markdown', 'image']),
        ];
    }
}
