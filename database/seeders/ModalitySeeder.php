<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Files;
use App\Models\ModalityAuditory;
use App\Models\ModalityKinesthetic;
use App\Models\ModalityReading;
use App\Models\ModalityVisualization;
use App\Models\ModalityWriting;
use App\Models\User;
use Illuminate\Database\Seeder;

class ModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $db_file = Files::query()->find(1);
        $user = User::query()->find(1); 

        $user['has_assessment'] = false;

        $user->save();

        // rank it by the following order and provide message, reading, writing, auditory, kinesthetic, visualization
        Assessment::factory()->create([
            'user_id' => $user->id,
            'file_id' => $db_file->id,
            'rank' => 1,
            'modality' => 'reading',
            'message' => 'Reading is ranked highest based on your assessment because it is your strongest modality.',
        ]);

        Assessment::factory()->create([
            'user_id' => $user->id,
            'file_id' => $db_file->id,
            'rank' => 2,
            'modality' => 'writing',
            'message' => 'Writing is ranked second based on your assessment because it is a strong modality for you.',
        ]);

        Assessment::factory()->create([
            'user_id' => $user->id,
            'file_id' => $db_file->id,
            'rank' => 3,
            'modality' => 'auditory',
            'message' => 'Auditory is ranked third based on your assessment because it is a moderate modality for you.',
        ]);

        Assessment::factory()->create([
            'user_id' => $user->id,
            'file_id' => $db_file->id,
            'rank' => 4,
            'modality' => 'kinesthetic',
            'message' => 'Kinesthetic is ranked fourth based on your assessment because it is a less strong modality for you.',
        ]);
        // Create an Assessment for visualization modality
        Assessment::factory()->create([
            'user_id'  => $user->id,
            'file_id'  => $db_file->id,
            'rank'     => 5,
            'modality' => 'visualization',
            'message'  => 'Visualization is ranked fifth based on your assessment because it is your weakest modality.',
        ]);

        // Define test types and number of questions
        $testTypes = ['pre', 'post'];
        $numberOfQuestions = 3; // Adjust as needed

        // Define a mapping from response keys to model classes
        $responseToModelMap = [
            'visualization_pre_test' => 'pre',
            'visualization_post_test'=> 'post',
        ];

        foreach ($testTypes as $testType) {
            for ($i = 1; $i <= $numberOfQuestions; $i++) {
                // Generate the test data using the factory
                $modalityVisualization = ModalityVisualization::factory()->create([
                    'question_index' => $i,
                    'file_id'        => $db_file->id,
                    'test_type'      => $testType,
                    'question'       => "Visualization {$testType} test question {$i}",
                    'choices'        => json_encode([
                        "Option A for question {$i}",
                        "Option B for question {$i}",
                        "Option C for question {$i}",
                        "Option D for question {$i}",
                    ]),
                    'correct_answer' => "Option B for question {$i}",
                    'image_prompt'   => "An image depicting scene {$i} for visualization {$testType} test.",
                    // 'image_url' remains null
                ]);

                // Optionally, log the creation
                $this->command->info("Created Visualization {$testType} Test Question {$i}");
            }

        }
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
