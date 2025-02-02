<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Files>
 */
class FilesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'path' => $this->faker->url(),
            'name' => $this->faker->sentence(),
            'is_ready' => false,
            'gpt_batch_id' => $this->faker->uuid(),
            'type' => $this->faker->randomElement(['pdf', 'markdown', 'image']),
        ];
    }
}
