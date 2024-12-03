<?php

namespace Database\Seeders;

use App\Models\Files;
use App\Models\ModalityAuditory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create();
        Files::factory(100, [
            'owner_id' => $user->id,
        ])->create();
        User::factory(10)->create();
    }
}
