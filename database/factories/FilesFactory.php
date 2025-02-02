<?php

namespace Database\Factories;

use App\Models\Files;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Files>
 */
class FilesFactory extends Factory
{
    protected $model = Files::class;

    public function definition(): array
    {
        return [
            'path'         => $this->faker->url(),
            'name'         => $this->faker->sentence(),
            'study_notes'  => $this->faker->paragraph(),
            'is_ready'     => $this->faker->boolean(),
            'type'         => $this->faker->randomElement(['pdf', 'markdown', 'image']),
            'owner_id'     => User::factory(),
        ];
    }
}
