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
        $user = User::factory()->create();
        // visualization
        $visualization_context_file = Files::factory([
            'owner_id' => $user->id,
        ])->create();
        for ($i = 0; $i < 10; $i++) {
            $image_file = Files::factory([
                'owner_id' => $user->id,
            ])->create();
            ModalityVisualization::factory([
                'image_file_id' => $image_file->id,
                'context_file_id' => $visualization_context_file->id,
            ])->create();
            // scores
            for ($j = 0; $j < 10; $j++) {
                Scores::factory()->create([
                    'user_id' => $user->id,
                    'context_file_id' => $visualization_context_file->id,
                ]);
            }
        }
        // reading-writing
        $reading_writing_context_file = Files::factory([
            'owner_id' => $user->id,
        ])->create();
        for ($i = 0; $i < 10; $i++) {
            ModalityReadingWriting::factory([
                'context_file_id' => $reading_writing_context_file->id,
            ])->create();
            // scores
            for ($j = 0; $j < 10; $j++) {
                Scores::factory()->create([
                    'user_id' => $user->id,
                    'context_file_id' => $reading_writing_context_file->id,
                ]);
            }
        }
        // auditory
        $auditory_context_file = Files::factory([
            'owner_id' => $user->id,
        ])->create();
        for ($i = 0; $i < 10; $i++) {
            $audio_file = Files::factory([
                'owner_id' => $user->id,
            ])->create();
            ModalityAuditory::factory([
                'audio_file_id' => $audio_file->id,
                'context_file_id' => $auditory_context_file->id,
            ])->create();
            // scores
            for ($j = 0; $j < 10; $j++) {
                Scores::factory()->create([
                    'user_id' => $user->id,
                    'context_file_id' => $auditory_context_file->id,
                ]);
            }
        }
    }
}
