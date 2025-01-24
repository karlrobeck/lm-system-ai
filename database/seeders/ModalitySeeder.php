<?php

namespace Database\Seeders;

use App\Models\Files;
use App\Models\ModalityAuditory;
use App\Models\ModalityKinesthetic;
use App\Models\ModalityReading;
use App\Models\ModalityVisualization;
use App\Models\ModalityWriting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $db_file = Files::query()->find(1);
        
        for ($i = 1; $i <= 10; $i++) {

            $test_type = $i > 5 ? 'post' : 'pre';

            ModalityReading::factory()->create([
                'question_index' => $i,
                'correct_answer' => 'Abuja',
                'file_id' => $db_file->id,
                'test_type' => $test_type,
            ]);
            ModalityWriting::factory()->create([
                'question_index' => $i,
                'file_id' => $db_file->id,
                'test_type' => $test_type,
            ]);
            ModalityVisualization::factory()->create([
                'question_index' => $i,
                'file_id' => $db_file->id,
                'test_type' => $test_type,
            ]);
            ModalityAuditory::factory()->create([
                'question_index' => $i,
                'file_id' => $db_file->id,
                'test_type' => $test_type,
            ]);
            ModalityKinesthetic::factory()->create([
                'question_index' => $i,
                'file_id' => $db_file->id,
                'test_type' => $test_type,
            ]);
        }
    }
}
