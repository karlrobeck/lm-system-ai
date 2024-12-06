<?php

namespace Database\Seeders;

use App\Models\Files;
use App\Models\ModalityAuditory;
use App\Models\ModalityReadingWriting;
use App\Models\ModalityVisualization;
use App\Models\Scores;
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
        $user = User::factory([
            'email' => "testuser@gmail.com",
            'password' => "testpassword"
        ])->create();
        for ($h = 0; $h < 10; $h++) {
            $context_file = Files::factory([
                'owner_id' => $user->id,
            ])->create();
            for ($i = 0; $i < 10; $i++) {
                $image_file = Files::factory([
                    'owner_id' => $user->id,
                    'type' => 'image',
                ])->create();
                ModalityVisualization::factory([
                    'image_file_id' => $image_file->id,
                    'context_file_id' => $context_file->id,
                ])->create();
                // scores
                for ($j = 0; $j < 10; $j++) {
                    Scores::factory()->create([
                        'user_id' => $user->id,
                        'context_file_id' => $context_file->id,
                    ]);
                }
            }
            for ($i = 0; $i < 10; $i++) {
                ModalityReadingWriting::factory([
                    'context_file_id' => $context_file->id,
                ])->create();
                // scores
                for ($j = 0; $j < 10; $j++) {
                    Scores::factory()->create([
                        'user_id' => $user->id,
                        'context_file_id' => $context_file->id,
                    ]);
                }
            }
            // auditory
            for ($i = 0; $i < 10; $i++) {
                $audio_file = Files::factory([
                    'owner_id' => $user->id,
                    'type' => 'audio',
                ])->create();
                ModalityAuditory::factory([
                    'audio_file_id' => $audio_file->id,
                    'context_file_id' => $context_file->id,
                ])->create();
                // scores
                for ($j = 0; $j < 10; $j++) {
                    Scores::factory()->create([
                        'user_id' => $user->id,
                        'context_file_id' => $context_file->id,
                    ]);
                }
            }
        }
    }
}
