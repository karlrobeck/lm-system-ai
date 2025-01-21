<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a user
        ModelsUser::factory()->create([
            'name' => 'Test user',
            'email' => 'testing@gmail.com',
            'password' => bcrypt('randompassword'),
        ]);
    }
}
